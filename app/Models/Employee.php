<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\EmployeeStatus;
use App\Events\EmployeeSaved;
use App\Events\EmployeeCreated;
use App\Models\Traits\HasOnePunch;
use App\Models\Traits\BelongsToAfp;
use App\Models\Traits\BelongsToArs;
use App\Models\Traits\BelongsToSite;
use App\Models\Traits\HasInformation;
use App\Models\Traits\BelongsToProject;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToPosition;
use App\Models\Traits\HasManySuspensions;
use App\Models\Traits\BelongsToDepartment;
use App\Models\Traits\BelongsToSupervisor;
use App\Models\Traits\HasManyTerminations;
use App\Models\Traits\BelongsToCitizenship;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, SoftDeletes, BelongsToSite, BelongsToProject, HasInformation, BelongsToPosition, BelongsToDepartment, BelongsToCitizenship, BelongsToSupervisor, BelongsToAfp, BelongsToArs, HasManyTerminations, HasManySuspensions;
    use HasOnePunch;
    protected $casts = [
        'date_of_birth' => 'datetime:Y-m-d',
        'hired_at' => 'datetime:Y-m-d',
        'status' => EmployeeStatus::class,
        'marriage' => MaritalStatus::class,
        'gender' => Gender::class,
    ];

    protected $fillable = ['first_name', 'second_first_name', 'last_name', 'second_last_name', 'full_name', 'personal_id', 'hired_at', 'date_of_birth', 'cellphone', 'status', 'marriage', 'gender', 'kids', 'site_id', 'project_id', 'position_id', 'citizenship_id', 'supervisor_id', 'afp_id', 'ars_id'];

    protected $dispatchesEvents = [
        'saved' => EmployeeSaved::class,
        'created' => EmployeeCreated::class
    ];

    public function updateFullName()
    {
        $name = trim(
            join(' ', array_filter([
                $this->first_name,
                $this->second_first_name,
                $this->last_name,
                $this->second_last_name,
            ]))
        );

        $this->updateQuietly(['full_name' => $name]);
    }

    public function getTenureAttribute()
    {
        return $this->hired_at->diffInDays(now());
    }

    public function scopeCurrent($query)
    {
        $query->where('status', EmployeeStatus::Current);
    }

    public function scopeSuspended($query)
    {
        $query->where('status', EmployeeStatus::Suspended);
    }

    public function scopeInactive($query)
    {
        $query->where('status', EmployeeStatus::Inactive);
    }

    public function scopeNotInactive($query)
    {
        $query->where('status', '<>', EmployeeStatus::Inactive);
    }

    public function scopeHasActiveSuspension($query)
    {
        $query->with('suspensions')
            ->where('status', '<>', EmployeeStatus::Inactive)
            ->where(function ($query) {
                $query->whereHas('suspensions', function ($suspensions) {
                    $suspensions->active();
                });
            });
    }

    public function scopeMissingActiveSuspension($query)
    {
        $query->with('suspensions')
            ->where(function ($query) {
                $query->whereDoesntHave('suspensions', function ($suspensions) {
                    $suspensions->active();
                });
            });
    }

    public function shouldBeSuspended(): bool
    {
        if ($this->status === EmployeeStatus::Inactive) {
            return false;
        }

        return $this->suspensions()->active()->count() > 0;
    }

    public function shouldNotBeSuspended(): bool
    {
        if ($this->status === EmployeeStatus::Inactive) {
            return false;
        }

        return $this->suspensions()->active()->count() === 0;
    }

    public function suspend()
    {
        $this->updateQuietly(['status' => EmployeeStatus::Suspended]);
    }

    public function unSuspend()
    {
        $this->updateQuietly(['status' => EmployeeStatus::Current]);
    }
}
