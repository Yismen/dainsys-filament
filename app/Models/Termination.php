<?php

namespace App\Models;

use App\Enums\EmployeeStatuses;
use App\Models\Traits\HasManyComments;
use App\Events\EmployeeTerminatedEvent;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exceptions\TerminationDateCantBeLowerThanHireDate;

class Termination extends \App\Models\BaseModels\AppModel
{
    use BelongsToEmployee;
    use HasManyComments;
    use SoftDeletes;

    protected $fillable = ['employee_id', 'date', 'termination_type', 'is_rehireable', 'comment'];

    protected $dispatchesEvents = [
        'created' => EmployeeTerminatedEvent::class,
    ];

    protected $casts = [
        'date' => 'datetime',
        'is_rehireable' => 'boolean',
    ];

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($termination) {
            if ($termination->employee->canBeTerminated() === false) {
                throw new \App\Exceptions\EmployeeCantBeTerminated;
            }

            $latestHireDate = $termination->employee->latestHire()?->date;

            if ($latestHireDate && $latestHireDate > $termination->date) {
                throw new TerminationDateCantBeLowerThanHireDate;
            }
        });

        static::created(function (Termination $termination) {
            $employee = $termination->employee;
            $employee->status = EmployeeStatuses::Terminated;
            $employee->terminated_at = $termination->date;
            $employee->save();
        });

        static::updated(function ($termination) {
            $termination->employee->touch();
        });
    }
}
