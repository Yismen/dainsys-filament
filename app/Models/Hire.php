<?php

namespace App\Models;

use App\Events\EmployeeHiredEvent;
use App\Models\Traits\BelongsToCitizenship;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\BelongsToPosition;
use App\Models\Traits\BelongsToProject;
use App\Models\Traits\BelongsToSite;
use App\Models\Traits\BelongsToSupervisor;
use App\Models\Traits\HasManyComments;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    protected $dispatchesEvents = [
        'created' => EmployeeHiredEvent::class,
    ];

    protected static function booted()
    {
        static::creating(function (Hire $hire) {
            if ($hire->employee->canBeHired() === false) {
                throw new \App\Exceptions\EmployeeCantBeHired;
            }
        });

        static::created(function (Hire $hire) {
            // Update employee's current assignment fields to match this new hire
            $hire->employee->update([
                'site_id' => $hire->site_id,
                'project_id' => $hire->project_id,
                'position_id' => $hire->position_id,
                'supervisor_id' => $hire->supervisor_id,
                'hired_at' => $hire->date,
            ]);
        });

        static::saved(function (Hire $hire) {
            $hire->employee->touch();
        });
    }
}
