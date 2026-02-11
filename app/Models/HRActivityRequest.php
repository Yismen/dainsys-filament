<?php

namespace App\Models;

use App\Enums\HRActivityRequestStatuses;
use App\Enums\HRActivityTypes;
use App\Events\HRActivityRequestCompleted;
use App\Events\HRActivityRequestCreated;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HRActivityRequest extends \App\Models\BaseModels\AppModel
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'supervisor_id',
        'activity_type',
        'status',
        'description',
        'completion_comment',
        'requested_at',
        'completed_at',
    ];

    protected $dispatchesEvents = [
        'created' => HRActivityRequestCreated::class,
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function markAsCompleted(string $comment): void
    {
        $this->update([
            'status' => HRActivityRequestStatuses::Completed,
            'completion_comment' => $comment,
            'completed_at' => now(),
        ]);

        HRActivityRequestCompleted::dispatch($this, $comment);
    }

    protected function casts(): array
    {
        return [
            'activity_type' => HRActivityTypes::class,
            'status' => HRActivityRequestStatuses::class,
            'requested_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }
}
