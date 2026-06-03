<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Services\EmployeesNeedingRemoveSuspension;
use App\Services\EmployeesNeedingSuspension;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Check if employees\'s status needs to be updated based on suspensions.')]
#[Signature('dainsys:update-employee-suspensions')]
class UpdateEmployeeSuspensions extends Command
{
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $employeesTosuspend = EmployeesNeedingSuspension::list();
        $SuspendedEmployeesToActivate = EmployeesNeedingRemoveSuspension::list();

        $employeesTosuspendCount = $employeesTosuspend->count();
        $SuspendedEmployeesToActivateCount = $SuspendedEmployeesToActivate->count();

        if ($employeesTosuspendCount) {
            $employeesTosuspend->each(function (Employee $employee): void {
                $employee->suspensions->each->touch();
                $employee->touch();
            });
            $this->info("{$employeesTosuspendCount} employees suspended!");
        }

        if ($SuspendedEmployeesToActivateCount) {
            $SuspendedEmployeesToActivate->each(function (Employee $employee): void {
                $employee->suspensions->each->touch();
                $employee->touch();
            });
            $this->info("{$SuspendedEmployeesToActivateCount} suspended employees activated!");
        }

        if ($SuspendedEmployeesToActivateCount === 0 && $SuspendedEmployeesToActivateCount === 0) {
            $this->warn('No status change needed for employees!');
        }

        return 0;
    }
}
