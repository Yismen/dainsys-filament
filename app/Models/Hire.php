<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\EmployeeStatus;
use App\Events\EmployeeSaved;
use App\Events\EmployeeHiredEvent;
use App\Models\Traits\BelongsToAfp;
use App\Models\Traits\BelongsToArs;
use App\Models\Traits\BelongsToSite;
use App\Models\Traits\HasInformation;
use App\Models\Traits\HasManyComments;
use App\Models\Traits\BelongsToProject;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToEmployee;
use App\Models\Traits\BelongsToPosition;
use App\Models\Traits\HasManyLoginNames;
use App\Models\Traits\HasManySuspensions;
use App\Models\Traits\BelongsToDepartment;
use App\Models\Traits\BelongsToSupervisor;
use App\Models\Traits\HasManyTerminations;
use App\Models\Traits\BelongsToCitizenship;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hire extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToEmployee;
    use BelongsToSite;
    use BelongsToProject;
    use BelongsToPosition;
    use BelongsToCitizenship;
    use BelongsToSupervisor;
    use HasManyComments;
    use HasUuids;

    protected $fillable = [
        'date',
        'employee_id',
        'site_id',
        'project_id',
        'position_id',
        'supervisor_id',
        'punch',
    ];

    protected $dispatchesEvents = [
        'created' => EmployeeHiredEvent::class
    ];
}
