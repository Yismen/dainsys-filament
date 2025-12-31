<?php

namespace App\Models;

use App\Events\TerminationCreated;
use App\Models\Traits\HasManyComments;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Exceptions\TerminationDateCantBeLowerThanHireDate;

class Termination extends \App\Models\BaseModels\AppModel
{
    use BelongsToEmployee;
    use HasManyComments;
    use SoftDeletes;

    protected $fillable = ['employee_id', 'date', 'termination_type', 'is_rehireable'];

    protected $dispatchesEvents = [
        'created' => TerminationCreated::class,
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_rehireable' => 'boolean',
    ];

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($termination) {
            if ($termination->employee->status !== \App\Enums\EmployeeStatuses::Hired) {
                throw new \App\Exceptions\EmployeeCantBeTerminated();
            }

            $latestHireDate = $termination->employee->latestHire()?->date;

            if($latestHireDate && $latestHireDate > $termination->date) {
                throw new TerminationDateCantBeLowerThanHireDate();
            }
        });

        static::saved(function ($termination) {
            $termination->employee->touch();
        });
    }
}
