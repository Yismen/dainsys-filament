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
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hire extends Model
{
    use BelongsToCitizenship;
    use BelongsToEmployee;
    use BelongsToPosition;
    use BelongsToProject;
    use BelongsToSite;
    use BelongsToSupervisor;
    use HasFactory;
    use HasManyComments;
    use HasUuids;
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

    protected $dispatchesEvents = [
        'created' => EmployeeHiredEvent::class,
    ];
}
