<?php

namespace App\Console\Commands;

use App\Enums\EmployeeStatuses;
use App\Enums\SuspensionStatuses;
use App\Models\Suspension;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Check if employees\'s status needs to be updated based on suspensions.')]
#[Signature('dainsys:update-pending-suspensions')]
class UpdatePendingSuspensions extends Command
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
        $suspensions = Suspension::query()
            ->with('employee')
            ->whereHas('employee', function ($employeeQuery): void {
                $employeeQuery->where('status', '<>', EmployeeStatuses::Terminated);
            })
            ->where(function ($query): void {
                $query->where('status', SuspensionStatuses::Current)
                    ->orWhere('status', SuspensionStatuses::Pending);
            })
            ->get()
            ->each(fn (Suspension $suspension) => $suspension->touch());

        $this->info("A total of {$suspensions->count()} were updated");

        return 0;
    }
}
