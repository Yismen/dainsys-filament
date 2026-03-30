<?php

namespace App\Models;

use App\Enums\EvaluationStatuses;
use App\Enums\QARoles;
use App\Models\BaseModels\AppModel;
use App\Notifications\EvaluationCreatedNotification;
use App\Notifications\EvaluationDisputedNotification;
use App\Notifications\EvaluationPublishedNotification;
use App\Notifications\EvaluationResolvedNotification;
use Database\Factories\EvaluationFactory;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Evaluation extends AppModel
{
    /** @use HasFactory<EvaluationFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'record_number',
        'evaluation_date',
        'employee_id',
        'supervisor_id',
        'evaluator_id',
        'qa_form_id',
        'threshold_percentage',
        'points_possible',
        'points_achieved',
        'success_percentage',
        'comments',
        'status',
        'employee_decision_comment',
        'manager_resolution_comment',
        'published_at',
        'accepted_at',
        'disputed_at',
        'resolved_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $evaluation): void {
            if (blank($evaluation->record_number)) {
                $evaluation->record_number = 'EVAL-'.strtoupper(Str::random(8));
            }

            if ($evaluation->status === null) {
                $evaluation->status = EvaluationStatuses::Draft;
            }

            if ($evaluation->threshold_percentage === null && $evaluation->qaForm !== null) {
                $evaluation->threshold_percentage = $evaluation->qaForm->passing_threshold_percentage;
            }

            if ($evaluation->supervisor_id === null && $evaluation->employee !== null) {
                $evaluation->supervisor_id = $evaluation->employee->supervisor_id;
            }
        });

        static::created(function (self $evaluation): void {
            $evaluation->recordStatusTransition(
                fromStatus: null,
                toStatus: EvaluationStatuses::Draft,
                changedBy: $evaluation->evaluator_id,
                comment: 'Evaluation created in draft status.'
            );

            $evaluation->notifyCreatedParties();
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function qaForm(): BelongsTo
    {
        return $this->belongsTo(QAForm::class, 'qa_form_id');
    }

    public function questionScores(): HasMany
    {
        return $this->hasMany(EvaluationQuestionScore::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(EvaluationStatusHistory::class);
    }

    public function recalculateScores(): void
    {
        $scores = $this->questionScores()->get(['points_awarded', 'max_points_snapshot']);

        $pointsPossible = (int) $scores->sum('max_points_snapshot');
        $pointsAchieved = (int) $scores->sum(
            fn (EvaluationQuestionScore $score): float => (($score->points_awarded ?? 0) / 100) * $score->max_points_snapshot
        );

        $successPercentage = $pointsPossible === 0
            ? 0
            : round(($pointsAchieved / $pointsPossible) * 100, 3);

        $this->updateQuietly([
            'points_possible' => $pointsPossible,
            'points_achieved' => $pointsAchieved,
            'success_percentage' => $successPercentage,
        ]);
    }

    public function publish(int|string $changedBy, ?string $comment = null): void
    {
        if ($this->status !== EvaluationStatuses::Draft) {
            throw new DomainException('Only draft evaluations can be published.');
        }

        $this->recalculateScores();

        if ($this->points_possible < 1) {
            throw new DomainException('Evaluation cannot be published without scored questions.');
        }

        $previousStatus = $this->status;

        $this->update([
            'status' => EvaluationStatuses::Published,
            'published_at' => now(),
        ]);

        $this->recordStatusTransition($previousStatus, EvaluationStatuses::Published, $changedBy, $comment);
        $this->notifyPublishedParties();
    }

    public function acceptByEmployee(int|string $changedBy, ?string $comment = null): void
    {
        if (! in_array($this->status, [EvaluationStatuses::Published, EvaluationStatuses::Disputed], true)) {
            throw new DomainException('Only published or disputed evaluations can be accepted.');
        }

        $previousStatus = $this->status;

        $this->update([
            'status' => EvaluationStatuses::AcceptedClosed,
            'employee_decision_comment' => $comment,
            'accepted_at' => now(),
            'resolved_at' => now(),
        ]);

        $this->recordStatusTransition($previousStatus, EvaluationStatuses::AcceptedClosed, $changedBy, $comment);
    }

    public function disputeByEmployee(int|string $changedBy, string $comment): void
    {
        if ($this->status !== EvaluationStatuses::Published) {
            throw new DomainException('Only published evaluations can be disputed.');
        }

        $previousStatus = $this->status;

        $this->update([
            'status' => EvaluationStatuses::Disputed,
            'employee_decision_comment' => $comment,
            'disputed_at' => now(),
        ]);

        $this->recordStatusTransition($previousStatus, EvaluationStatuses::Disputed, $changedBy, $comment);
        $this->notifyDisputedParties();
    }

    public function resolveDispute(
        EvaluationStatuses $resolutionStatus,
        int|string $changedBy,
        string $comment
    ): void {
        if ($this->status !== EvaluationStatuses::Disputed) {
            throw new DomainException('Only disputed evaluations can be resolved.');
        }

        if (! in_array($resolutionStatus, [EvaluationStatuses::AcceptedClosed, EvaluationStatuses::Rejected], true)) {
            throw new DomainException('Disputes can only be resolved as accepted_closed or rejected.');
        }

        $previousStatus = $this->status;

        $this->update([
            'status' => $resolutionStatus,
            'manager_resolution_comment' => $comment,
            'resolved_at' => now(),
            'accepted_at' => $resolutionStatus === EvaluationStatuses::AcceptedClosed ? now() : $this->accepted_at,
        ]);

        $this->recordStatusTransition($previousStatus, $resolutionStatus, $changedBy, $comment);
        $this->notifyResolvedParties();
    }

    public function notifyCreatedParties(): void
    {
        $notification = new EvaluationCreatedNotification($this);

        $this->collectRecipientsForCreation()->each(function (User $user) use ($notification): void {
            $user->notify($notification);
        });
    }

    public function notifyPublishedParties(): void
    {
        $notification = new EvaluationPublishedNotification($this);

        $this->collectEmployeeAndSupervisorRecipients()->each(function (User $user) use ($notification): void {
            $user->notify($notification);
        });
    }

    public function notifyDisputedParties(): void
    {
        $notification = new EvaluationDisputedNotification($this);

        User::query()
            ->role(QARoles::Manager->value)
            ->get()
            ->each(function (User $user) use ($notification): void {
                $user->notify($notification);
            });
    }

    public function notifyResolvedParties(): void
    {
        $notification = new EvaluationResolvedNotification($this);

        $this->collectRecipientsForResolution()->each(function (User $user) use ($notification): void {
            $user->notify($notification);
        });
    }

    protected function collectRecipientsForCreation(): Collection
    {
        return $this->collectEmployeeAndSupervisorRecipients()
            ->merge([$this->evaluator])
            ->merge(User::query()->role(QARoles::Manager->value)->get())
            ->filter()
            ->unique('id')
            ->values();
    }

    protected function collectRecipientsForResolution(): Collection
    {
        return $this->collectEmployeeAndSupervisorRecipients()
            ->merge([$this->evaluator])
            ->filter()
            ->unique('id')
            ->values();
    }

    protected function collectEmployeeAndSupervisorRecipients(): Collection
    {
        return collect([
            $this->employee?->user,
            $this->supervisor?->user,
        ])->filter();
    }

    protected function recordStatusTransition(
        EvaluationStatuses|string|null $fromStatus,
        EvaluationStatuses|string $toStatus,
        int|string $changedBy,
        ?string $comment = null
    ): void {
        $from = $fromStatus instanceof EvaluationStatuses ? $fromStatus->value : $fromStatus;
        $to = $toStatus instanceof EvaluationStatuses ? $toStatus->value : $toStatus;

        $this->statusHistories()->create([
            'from_status' => $from,
            'to_status' => $to,
            'changed_by' => $changedBy,
            'change_comment' => $comment,
        ]);
    }

    protected function casts(): array
    {
        return [
            'status' => EvaluationStatuses::class,
            'evaluation_date' => 'date',
            'threshold_percentage' => 'float',
            'success_percentage' => 'float',
            'published_at' => 'datetime',
            'accepted_at' => 'datetime',
            'disputed_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }
}
