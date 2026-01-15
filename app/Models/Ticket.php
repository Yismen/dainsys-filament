<?php

namespace App\Models;

use App\Enums\TicketPriorities;
use App\Enums\TicketStatuses;
use App\Events\TicketAssignedEvent;
use App\Events\TicketCompletedEvent;
use App\Events\TicketCreatedEvent;
use App\Events\TicketDeletedEvent;
use App\Events\TicketReopenedEvent;
use App\Traits\EnsureDateNotWeekend;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Ticket extends \App\Models\BaseModels\AppModel
{
    use EnsureDateNotWeekend;
    use SoftDeletes;

    protected $fillable = [
        'owner_id',
        'subject',
        'description',
        // 'reference',
        'images',
        'status',
        'priority',
        'expected_at',
        'assigned_to',
        'assigned_at',
        'completed_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'expected_at' => 'datetime',
        'completed_at' => 'datetime',
        'images' => 'array',
        'status' => TicketStatuses::class,
        'priority' => TicketPriorities::class,
    ];

    // protected $appends = [
    //     'reference',
    // ];

    protected string $tickets_prefix = 'ECCOSTGOIT_';

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->owner_id = Auth::id();
            }
        });

        static::created(function (Ticket $model) {
            $model->status = TicketStatuses::Pending;
            $model->reference = $model->getReference();

            $model->saveQuietly();

            TicketCreatedEvent::dispatch($model);
        });

        static::saved(function (Ticket $model) {
            $model->expected_at = $model->getExpectedDate();
            $model->status = $model->getStatus();

            $model->saveQuietly();
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

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function agent(): BelongsTo
    {
        return $this->operator();
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function assignTo(string|int|User $agent)
    {
        $this->update([
            'assigned_to' => $agent instanceof User ? $agent->id : $agent,
            'assigned_at' => now(),
            'status' => $this->getStatus(),
        ]);

        TicketAssignedEvent::dispatch($this);
    }

    public function grab()
    {
        $this->assignTo(Auth::user());
    }

    public function reOpen(string $comment)
    {
        $this->replies()->createQuietly([
            'user_id' => Auth::id(),
            'content' => $comment,
        ]);

        $this->update([
            'status' => $this->getStatus(),
            'completed_at' => null,
        ]);

        TicketReopenedEvent::dispatch($this, $comment);
    }

    public function complete(string $comment)
    {
        $this->replies()->createQuietly([
            'user_id' => Auth::id(),
            'content' => $comment,
        ]);

        $this->update([
            'status' => $this->getStatus(),
            'completed_at' => now(),
        ]);

        TicketCompletedEvent::dispatch($this, $comment);
    }

    public function close(string $comment)
    {
        $this->complete($comment);
    }

    public function isAssigned(): bool
    {
        return ! is_null($this->assigned_to);
    }

    public function isAssignedTo(User $user): bool
    {
        return $this->assigned_to === $user->id;
    }

    public function isAssignedToMe(): bool
    {
        return $this->assigned_to === Auth::id();
    }

    public function isOpen(): bool
    {
        return is_null($this->completed_at);
    }

    public function isClosed(): bool
    {
        return !$this->isOpen();
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

    public function scopeInProgress(Builder $query): Builder
    {
        return $query
            ->where('assigned_to', '!=', null)
            ->where('completed_at', null);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query
            ->where('assigned_to', '=', null);
    }

    public function scopeCompliant(Builder $query): Builder
    {
        return $query->whereColumn('completed_at', '<', 'expected_at')
            ->where('completed_at', '!=', null);
    }

    public function scopeNonCompliant(Builder $query): Builder
    {
        return $query->whereColumn('completed_at', '>=', 'expected_at')
            ->where('completed_at', '!=', null);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expected_at', '<', now());
    }

    public function getImagePathAttribute()
    {
        return Storage::url($this->image).'?'.Str::random(5);
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
            ->first()?->reference;

        if ($latest_reference != null) {
            $reference = str($latest_reference)->after($this->tickets_prefix)->toString();

            $reference = ++$reference;

            return $this->tickets_prefix . str($reference)->padLeft(6, '0');
        }

        return $this->tickets_prefix.'000001';
    }

    // public function getReferenceAttribute()
    // {
    //     return $this->attributes['reference'] ?? null;
    // }
}
