<?php

namespace App\Models;

use App\Enums\EmployeeStatuses;
use App\Enums\Genders;
use App\Enums\PersonalIdTypes;
use App\Models\Traits\BelongsToCitizenship;
use App\Models\Traits\HasManyDowntimes;
use App\Models\Traits\HasManyHires;
use App\Models\Traits\HasManyLoginNames;
use App\Models\Traits\HasManyPayrollHours;
use App\Models\Traits\HasManyProductions;
use App\Models\Traits\HasManySuspensions;
use App\Models\Traits\HasManyTerminations;
use App\Models\Traits\HasRelationsThruHire;
use App\Models\Traits\HasRelationsThruSocialSecurity;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Employee extends \App\Models\BaseModels\AppModel
{
    use BelongsToCitizenship;
    use HasManyDowntimes;
    use HasManyHires;
    use HasManyLoginNames;
    use HasManyPayrollHours;
    use HasManyProductions;
    use HasManySuspensions;
    use HasManyTerminations;
    use HasRelationsThruHire;
    use HasRelationsThruSocialSecurity;

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
        'secondary_phone',
        'email',
        'address',
        // 'status',
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
        'date_of_birth' => 'date:Y-m-d',
        'status' => EmployeeStatuses::class,
        'gender' => Genders::class,
        'has_kids' => 'boolean',
        'personal_id_type' => PersonalIdTypes::class,
    ];

    protected $dispatchesEvents = [
        // 'saved' => EmployeeSaved::class,
        // 'created' => EmployeeCreated::class
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function (Employee $employee) {
            $employee->status = EmployeeStatuses::Created;

            $employee->saveQuietly();
        });

        static::saved(function (Employee $employee) {
            $employee->full_name = $employee->getFullName();
            $employee->status = $employee->getStatus();

            $employee->saveQuietly();
        });
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

    // public function getTenureAttribute()
    // {
    //     return $this->hired_at->diffInDays(now());
    // }

    public function scopeActive($query)
    {
        $query->whereIn('status', [
            EmployeeStatuses::Hired,
            EmployeeStatuses::Suspended,
        ]);
    }

    public function scopeCurrent($query)
    {
        $query->whereIn('status', [
            EmployeeStatuses::Hired,
            EmployeeStatuses::Suspended,
        ]);
    }

    public function scopeSuspended($query)
    {
        $query->where('status', EmployeeStatuses::Suspended);
    }

    public function scopeInactive($query)
    {
        $query->where('status', EmployeeStatuses::Terminated);
    }

    public function scopeNotInactive($query)
    {
        $query->whereIn('status', [
            EmployeeStatuses::Hired,
            EmployeeStatuses::Suspended,
        ]);
    }

    public function scopeHasActiveSuspension($query)
    {
        $query->with('suspensions')
            ->current()
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
        if ($this->status === EmployeeStatuses::Terminated) {
            return false;
        }

        return $this->suspensions()->active()->count() > 0;
    }

    public function shouldNotBeSuspended(): bool
    {
        if ($this->status === EmployeeStatuses::Terminated) {
            return false;
        }

        return $this->suspensions()->active()->count() === 0;
    }

    public function latestHire(): ?Hire
    {
        return $this->hires()->latest('date')->first();
    }

    public function latestTermination(): ?Termination
    {
        return $this->terminations()->latest('date')->first();
    }

    public function latestActiveSuspension(): ?Suspension
    {
        return $this->suspensions()
            ->active()
            ->latest('starts_at')
            ->first();
    }

    protected function getStatus()
    {
        $latestHire = $this->latestHire();
        $latestActiveSuspension = $this->latestActiveSuspension();
        $latestTermination = $this->latestTermination();

        if ($latestHire && $latestHire->date > $latestTermination?->date) { // the employee is active
            if ($latestActiveSuspension) { // active but suspended
                return EmployeeStatuses::Suspended;
            }

            return EmployeeStatuses::Hired;
        }

        if ($latestTermination && $latestTermination->date >= $latestHire?->date /** && $latestTermination->date >= now() */) { // should be terminated
            return EmployeeStatuses::Terminated;
        }

        return EmployeeStatuses::Created;
    }

    protected function getFullName()
    {
        return trim(
            implode(' ', array_filter([
                $this->first_name,
                $this->second_first_name,
                $this->last_name,
                $this->second_last_name,
            ]))
        );
    }

    public function canBeHired(): bool
    {
        return $this->status === EmployeeStatuses::Created ||
            (
                $this->status === EmployeeStatuses::Terminated &&
                $this->latestTermination()?->is_rehireable === true
            );
    }

    public function canBeTerminated(): bool
    {
        return $this->status === EmployeeStatuses::Hired;
    }

    public function canBeSuspended(): bool
    {
        return $this->status === EmployeeStatuses::Hired;
    }
}
