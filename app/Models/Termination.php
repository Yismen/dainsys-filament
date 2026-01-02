<?php

namespace App\Models;

use App\Events\TerminationCreatedEvent;
use App\Exceptions\TerminationDateCantBeLowerThanHireDate;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\HasManyComments;
use Illuminate\Database\Eloquent\SoftDeletes;

class Termination extends \App\Models\BaseModels\AppModel
{
    use BelongsToEmployee;
    use HasManyComments;
    use SoftDeletes;

    protected $fillable = ['employee_id', 'date', 'termination_type', 'is_rehireable', 'comment'];

    protected $dispatchesEvents = [
        'created' => TerminationCreatedEvent::class,
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

        static::saved(function ($termination) {
            $termination->employee->touch();
        });
    }
}
