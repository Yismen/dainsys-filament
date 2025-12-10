<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\EmployeeStatus;
use App\Events\EmployeeSaved;
use App\Events\EmployeeCreated;
use App\Models\Traits\BelongsToAfp;
use App\Models\Traits\BelongsToArs;
use App\Models\Traits\BelongsToSite;
use App\Models\Traits\HasInformation;
use App\Models\Traits\BelongsToProject;
use App\Models\Traits\HasManyDowntimes;
use App\Models\Traits\HasManyHires;
use App\Models\Traits\HasManyPayrollHours;
use App\Models\Traits\HasManyProductions;
use App\Models\Traits\HasRelationsThruHire;
use App\Models\Traits\HasRelationsThruSocialSecurity;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToPosition;
use App\Models\Traits\HasManyLoginNames;
use App\Models\Traits\HasManySuspensions;
use App\Models\Traits\BelongsToDepartment;
use App\Models\Traits\HasManyTerminations;
use App\Models\Traits\BelongsToCitizenship;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasInformation;
    use BelongsToCitizenship;
    use HasManyHires;
    use HasManyTerminations;
    use HasManySuspensions;
    use HasManyLoginNames;
    use HasRelationsThruHire;
    use HasRelationsThruSocialSecurity;
    use HasManyProductions;
    use HasManyDowntimes;
    use HasManyPayrollHours;
    use HasUuids;

    protected $fillable = [
        'first_name',
        'second_first_name',
        'last_name',
        'second_last_name',
        // 'full_name',
        'personal_id_type',
        'personal_id',
        'date_of_birth',
        'cellphone',
        'status',
        'gender',
        'has_kids',
        'citizenship_id',
        // 'punch',
        // 'site_id',
        // 'project_id',
        // 'position_id',
        // 'supervisor_id',
        // 'afp_id',
        // 'ars_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'status' => EmployeeStatus::class,
        'marriage' => MaritalStatus::class,
        'gender' => Gender::class,
    ];


    protected $dispatchesEvents = [
        // 'saved' => EmployeeSaved::class,
        // 'created' => EmployeeCreated::class
    ];


    protected static function boot()
    {
        parent::boot();

        static::saved(function ($employee) {
            $employee->updateFullName();
        });
    }

    public function updateFullName()
    {
        $this->full_name = trim(
            join(' ', array_filter([
                $this->first_name,
                $this->second_first_name,
                $this->last_name,
                $this->second_last_name,
            ]))
        );

        $this->saveQuietly();
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

    public function supervisor(): HasOneThrough
    {
        return $this->hasOneThrough(
            Supervisor::class,
            Hire::class,
            'employee_id', // Foreign key on hires table...
            'id', // Foreign key on supervisors table...
            'id', // Local key on employees table...
            'supervisor_id' // Local key on hires table...
        );
    }
}
