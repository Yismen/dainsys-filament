<?php

namespace App\Models;

use App\Enums\EmployeeStatuses;
use App\Events\SuspensionUpdatedEvent;
use App\Exceptions\SuspensionDateCantBeLowerThanHireDate;
use App\Models\Traits\HasManyComments;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\BelongsToSuspensionType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Suspension extends \App\Models\BaseModels\AppModel
{
    use BelongsToEmployee;
    use BelongsToSuspensionType;
    use HasManyComments;

    protected $fillable = ['employee_id', 'suspension_type_id', 'starts_at', 'ends_at'];

    protected $casts = [
        'starts_at' => 'date:Y-m-d',
        'ends_at' => 'date:Y-m-d',
    ];

    protected $dispatchesEvents = [
        'saved' => SuspensionUpdatedEvent::class,
    ];

    protected static function booted()
    {
        static::creating(function ($suspension) {
            if ($suspension->employee->status != EmployeeStatuses::Hired) {
                throw new \App\Exceptions\EmployeeCantBeSuspended();
            }

            $latestHireDate = $suspension->employee->latestHire()?->date;

            if($latestHireDate && $latestHireDate > $suspension->starts_at) {
                throw new SuspensionDateCantBeLowerThanHireDate();
            }
        });

        static::created(function (Suspension $suspension) {
            $suspension->employee->touch();
        });


    }

    public function getDurationAttribute()
    {
        return $this->starts_at ? $this->starts_at->diffInDays($this->ends_at) + 1 .' days' : null;
    }

    public function scopeActive($query)
    {
        $query->where(function ($query) {
            $query
                ->whereDate('starts_at', '<=', now()->format('Y-m-d'))
                ->whereDate('ends_at', '>=', now()->format('Y-m-d'));
        });
    }

    public function scopeInactive($query)
    {
        $query->where(function ($query) {
            $query
                ->whereDate('starts_at', '>', now()->format('Y-m-d'))
                ->orWhereDate('ends_at', '<', now()->format('Y-m-d'));
        });
    }

    public function getIsActiveAttribute(): bool
    {
        return now()->isBetween($this->starts_at->startOfDay(), $this->ends_at->endOfDay());
    }
}
