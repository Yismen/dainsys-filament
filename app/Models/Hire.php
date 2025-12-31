<?php

namespace App\Models;

use App\Enums\EmployeeStatuses;
use App\Events\EmployeeHiredEvent;
use App\Models\Traits\BelongsToSite;
use App\Models\Traits\HasManyComments;
use App\Models\Traits\BelongsToProject;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\BelongsToPosition;
use App\Models\Traits\BelongsToSupervisor;
use App\Models\Traits\BelongsToCitizenship;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hire extends \App\Models\BaseModels\AppModel
{
    use BelongsToCitizenship;
    use BelongsToEmployee;
    use BelongsToPosition;
    use BelongsToProject;
    use BelongsToSite;
    use BelongsToSupervisor;
    use HasManyComments;
    use SoftDeletes;

    protected $fillable = [
        'date',
        'employee_id',
        'site_id',
        'project_id',
        'position_id',
        'supervisor_id',
        'punch',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d'
    ];

    protected $dispatchesEvents = [
        'created' => EmployeeHiredEvent::class,
    ];

    protected static function booted()
    {
        static::creating(function (Hire $hire) {
            if ($hire->employee->status === EmployeeStatuses::Suspended || $hire->employee->status === EmployeeStatuses::Hired) {
                throw new \App\Exceptions\EmployeeCantBeHired();
            }
        });

        static::saved(function (Hire $hire) {
            $hire->employee->touch();
        });
    }
}
