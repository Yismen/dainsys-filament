<?php

namespace App\Models;

use App\Enums\SuspensionStatuses;
use App\Events\EmployeeSuspendedEvent;
use App\Exceptions\SuspensionDateCantBeLowerThanHireDate;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\BelongsToSuspensionType;
use App\Models\Traits\HasManyComments;

class Suspension extends \App\Models\BaseModels\AppModel
{
    use BelongsToEmployee;
    use BelongsToSuspensionType;
    use HasManyComments;

    protected $fillable = ['employee_id', 'suspension_type_id', 'starts_at', 'ends_at', 'comment'];

    protected $dispatchesEvents = [
        'created' => EmployeeSuspendedEvent::class,
    ];

    protected static function booted()
    {
        static::creating(function (Suspension $suspension): void {
            if ($suspension->employee->canBeSuspended() === false) {
                throw new \App\Exceptions\EmployeeCantBeSuspended;
            }

            $latestHireDate = $suspension->employee->latestHire()?->date;

            if ($latestHireDate && $latestHireDate > $suspension->starts_at) {
                throw new SuspensionDateCantBeLowerThanHireDate;
            }
        });

        static::saved(function (Suspension $suspension): void {
            if ($suspension->starts_at > now()) {
                $suspension->status = SuspensionStatuses::Pending;
            } elseif ($suspension->ends_at < now()) {
                $suspension->status = SuspensionStatuses::Completed;
            } else {
                $suspension->status = SuspensionStatuses::Current;
            }

            $suspension->saveQuietly();

            $suspension->employee->touch();
        });

    }

    protected function duration(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(get: function () {
            return $this->starts_at ? $this->starts_at->diffInDays($this->ends_at) + 1 .' days' : null;
        });
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function active($query)
    {
        $query->where(function ($query): void {
            $query
                ->whereDate('starts_at', '<=', now()->format('Y-m-d'))
                ->whereDate('ends_at', '>=', now()->format('Y-m-d'));
        });
    }

    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function inactive($query)
    {
        $query->where(function ($query): void {
            $query
                ->whereDate('starts_at', '>', now()->format('Y-m-d'))
                ->orWhereDate('ends_at', '<', now()->format('Y-m-d'));
        });
    }

    protected function isActive(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(get: function () {
            return now()->isBetween($this->starts_at->startOfDay(), $this->ends_at->endOfDay());
        });
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'status' => SuspensionStatuses::class,
        ];
    }
}
