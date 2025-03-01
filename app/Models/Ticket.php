<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Enums\TicketStatuses;
use App\Enums\TicketPriorities;
use App\Models\TicketDepartment;
use App\Events\TicketCreatedEvent;
use App\Events\TicketDeletedEvent;
use App\Events\TicketAssignedEvent;
use App\Events\TicketReopenedEvent;
use App\Events\TicketCompletedEvent;
use App\Traits\EnsureDateNotWeekend;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;
    use EnsureDateNotWeekend;
    use SoftDeletes;

    protected $fillable = ['owner_id', 'department_id', 'subject', 'description', 'reference', 'images', 'status', 'priority', 'expected_at', 'assigned_to', 'assigned_at', 'completed_at'];
    protected $casts = [
        'assigned_at' => 'datetime',
        'expected_at' => 'datetime',
        'completed_at' => 'datetime',
        'status' => TicketStatuses::class,
        'priority' => TicketPriorities::class,
    ];


    protected static function booted()
    {
        parent::booted();

        static::created(function ($model) {
            $model->updateQuietly([
                'status' => TicketStatuses::Pending,
                // 'assigned_to' => null,
                // 'assigned_at' => null,
                'reference' => $model->getReference(),
            ]);

            TicketCreatedEvent::dispatch($model);
        });

        static::saved(function ($model) {
            $model->updateQuietly([
                'expected_at' => $model->getExpectedDate(),
            ]);
            $model->updateQuietly([
                'status' => $model->getStatus(),
            ]);
        });
        static::deleting(function ($model) {
            // if ($model->image) {
            //     $imageCreatorService = new ImageCreatorService();

            //     $imageCreatorService->delete($model->image);
            // }
        });
        static::deleted(function ($model) {
            TicketDeletedEvent::dispatch($model);
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(TicketDepartment::class, 'department_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function assignTo(User $agent)
    {
        // if (is_integer($agent)) {
        //     $agent = DepartmentRole::findOrFail($agent);
        // }

        // if ($agent instanceof User) {
        //     $agent = DepartmentRole::where('user_id', $agent->id)->firstOrFail();
        // }

        // if ($agent->department_id != $this->department_id) {
        //     throw new DifferentDepartmentException();
        // }

        $this->update([
            'assigned_to' => $agent->id,
            'assigned_at' => now(),
            'status' => $this->getStatus(),
        ]);

        TicketAssignedEvent::dispatch($this);
    }

    public function reOpen()
    {
        $this->update([
            'status' => $this->getStatus(),
            'completed_at' => null,
        ]);

        TicketReopenedEvent::dispatch($this);
    }

    public function complete(string $comment = '')
    {
        $this->update([
            'status' => $this->getStatus(),
            'completed_at' => now(),
        ]);

        TicketCompletedEvent::dispatch($this, $comment);
    }

    public function close(string $comment)
    {
        $this->replies()->createQuietly([
            'user_id' => auth()->user()->id,
            'content' => $comment
        ]);

        $this->complete($comment);
    }

    public function isAssigned(): bool
    {
        return !is_null($this->assigned_to);
    }

    public function isAssignedTo(User $user): bool
    {
        return $this->assigned_to === $user->id;
    }

    public function isAssignedToMe(): bool
    {
        return $this->assigned_to === auth()->user()->id;
    }

    public function isOpen(): bool
    {
        return is_null($this->completed_at);
    }

    // public function isAssignedTo(DepartmentRole|User|int $agent): bool
    // {
    //     if (is_integer($agent)) {
    //         $agent = DepartmentRole::findOrFail($agent);
    //     }

    //     if ($agent instanceof User) {
    //         $agent = DepartmentRole::where('user_id', $agent->id)->firstOrFail();
    //     }
    //     return $this->assigned_to === $agent->user_id;
    // }

    // public function updateImage($image, string $path = 'tickets', $name = null, int $resize = 400, int $quality = 90)
    // {
    //     if ($image instanceof UploadedFile) {
    //         $imageCreatorService = new ImageCreatorService();

    //         $url = $imageCreatorService->make($image, $path, $name ?: $this->id, $resize, $quality);

    //         $this->updateQuietly([
    //             'image' => $url
    //         ]);
    //     }
    // }

    public function getStatus(): TicketStatuses
    {
        // Pendig or Pending Expired
        if ($this->assigned_to == null && $this->completed_at == null) {
            return $this->expected_at > now()
                ? TicketStatuses::Pending
                : TicketStatuses::PendingExpired;
        }

        //    In Progress or In Progress Expired
        if ($this->assigned_to && $this->completed_at == null) {
            return $this->expected_at > now()
                ? TicketStatuses::InProgress
                : TicketStatuses::InProgressExpired;
        }

        // Completed
        return $this->expected_at > $this->completed_at
            ? TicketStatuses::Completed
            : TicketStatuses::CompletedExpired;
    }

    public function scopeIncompleted(Builder $query): Builder
    {
        return $query->where('completed_at', null);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('completed_at', '!=', null);
    }

    public function scopeCompliant(Builder $query): Builder
    {
        return $query->whereColumn('completed_at', '<', 'expected_at');
    }

    public function scopeNonCompliant(Builder $query): Builder
    {
        return $query->whereColumn('completed_at', '>', 'expected_at');
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expected_at', '<', now());
    }

    public function getImagePathAttribute()
    {
        return Storage::url($this->image) . '?' . Str::random(5);
    }

    // public function getPriorityAttribute()
    // {
    //     return Cache::rememberForever('ticket_priority_' . $this->id, function () {
    //         return $this->priority->value;
    //     });
    // }

    public function getMailPriorityAttribute(): float|int
    {
        return $this->priority->value > 5 ? 5 : 5 - $this->priority->value;
    }

    // public function admins()
    // {
    //     return $this->department->roles()->where('role', DepartmentRolesEnum::Admin)->with('user')->get();
    // }

    protected function getExpectedDate(): Carbon
    {
        $date = $this->created_at ?? now();

        switch ($this->priority) {
            case TicketPriorities::Normal:
                return $this->ensureNotWeekend($date->copy()->addDays(2));
            case TicketPriorities::Medium:
                return $this->ensureNotWeekend($date->copy()->addDay());
            case TicketPriorities::High:
                return $this->ensureNotWeekend($date->copy()->addMinutes(4 * 60));
            case TicketPriorities::Emergency:
                return $this->ensureNotWeekend($date->copy()->addMinutes(30));

            default:
                return $this->ensureNotWeekend($date->copy()->addDays(2));
        }
    }

    protected function getReference(): string
    {
        $latest_reference = self::query()
            ->orderBy('reference', 'desc')
            ->where('department_id', $this->department_id)
            ->where('reference', 'like', "{$this->department->ticket_prefix}%")
            ->first();

        if ($latest_reference) {
            return ++$latest_reference->reference;
        }

        return $this->department->ticket_prefix . '000001';
    }
}
