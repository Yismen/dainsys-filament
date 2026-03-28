<?php

namespace App\Models;

use App\Enums\DowntimeStatuses;
use App\Enums\RevenueTypes;
use App\Models\BaseModels\AppModel;
use App\Models\Traits\BelongsToCampaign;
use App\Models\Traits\BelongsToDowntimeReason;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\HasManyComments;
use Database\Factories\DowntimeFactory;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Downtime extends AppModel
{
    use BelongsToCampaign;
    use BelongsToDowntimeReason;
    use BelongsToEmployee;

    /** @use HasFactory<DowntimeFactory> */
    use HasManyComments;

    use SoftDeletes;

    protected $fillable = [
        'date',
        'employee_id',
        'campaign_id',
        'downtime_reason_id',
        'total_time',
        'status',
        // 'requester_id',
        // 'aprover_id',
        'converted_to_payroll_at',
    ];

    protected static function booted()
    {
        parent::booted();

        static::creating(function (Downtime $downtime): void {
            $downtime->requester_id = auth()->user()?->id;
        });

        static::created(function (Downtime $downtime): void {
            $users = User::withWhereHas('roles', fn ($query) => $query->where('name', 'Workforce Manager'))->get();

            if ($users->isNotEmpty()) {
                Notification::make()
                    ->title('New Downtime Request')
                    ->body("A new downtime request has been created for employee {$downtime->employee?->full_name} on {$downtime->date->format('Y-m-d')}.")
                    ->sendToDatabase($users);
            }
        });

        static::saving(function (Downtime $downtime) {
            if ($downtime->campaign->revenue_type !== RevenueTypes::Downtime) {
                Notification::make()
                    ->title('New Downtime Request')
                    ->danger()
                    ->body('Only campaigns with revenue type of downtime are allowed.')
                    ->send();

                return false;
            }

            $uniqueId = implode('_', [
                $downtime->date->format('Y-m-d'),
                $downtime->campaign_id,
                $downtime->downtime_reason_id,
                $downtime->employee_id,
            ]);

            if (static::where('unique_id', $uniqueId)->where('id', '!=', $downtime->id)->exists()) {
                Notification::make()
                    ->title('New Downtime Request')
                    ->danger()
                    ->body('A downtime entry with the same date, campaign, reason, and employee already exists.')
                    ->send();

                return false;
            }

            $downtime->unique_id = $uniqueId;
        });

        static::saved(function (Downtime $downtime): void {
            // Track initial request as a comment only if none exists
            if ($downtime->wasRecentlyCreated && ! $downtime->comments()->exists()) {
                Comment::query()->forceCreate([
                    'text' => 'Requested by: '.(auth()->user()?->name ?? 'system'),
                    'commentable_id' => $downtime->id,
                    'commentable_type' => self::class,
                ]);
            }
        });

        static::updated(function (Downtime $downtime): void {
            $production = $downtime->production;
            if ($production) {
                $production->updateQuietly([
                    'date' => $downtime->date,
                    'campaign_id' => $downtime->campaign_id,
                    'employee_id' => $downtime->employee_id,
                    'downtime_reason_id' => $downtime->downtime_reason_id,
                    'total_time' => $downtime->total_time,
                ]);
            }
        });

        static::softDeleted(function (Downtime $downtime): void {
            $downtime->unAprove();
        });

        static::deleted(function (Downtime $downtime): void {
            $downtime->unAprove();
        });
    }

    public function production(): HasOne
    {
        return $this->hasOne(Production::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function aprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprover_id');
    }

    public function aprove()
    {
        DB::transaction(function (): void {
            $this->aprover_id = auth()->user()->id;
            $this->status = DowntimeStatuses::Approved;

            $this->saveQuietly();

            $this->syncInProductionsTable();
        });
    }

    public function unAprove()
    {
        $this->aprover_id = null;
        $this->status = DowntimeStatuses::Pending;

        $this->saveQuietly();

        $this->removeFromProduction();
    }

    protected function syncInProductionsTable()
    {
        Production::query()->updateOrCreate(
            [
                'downtime_id' => $this->id,
            ],
            [
                'date' => $this->date,
                'campaign_id' => $this->campaign_id,
                'employee_id' => $this->employee_id,
                'total_time' => $this->total_time,
            ]
        );
    }

    public function removeFromProduction()
    {
        Production::query()
            ->where('downtime_id', $this->id)
            ->first()
            ?->forceDelete();

    }

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'status' => DowntimeStatuses::class,
        ];
    }
}
