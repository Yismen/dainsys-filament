<?php

namespace App\Console\Commands;

use App\Enums\EmployeeStatuses;
use App\Services\EmployeesNeedingRemoveSuspension;
use App\Services\EmployeesNeedingSuspension;
use Illuminate\Console\Command;

class UpdateEmployeeSuspensions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:update-employee-suspensions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if employees\'s status needs to be updated based on suspensions.';

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
        $shouldSuspend = EmployeesNeedingSuspension::list();
        $shouldActivate = EmployeesNeedingRemoveSuspension::list();

        $shouldSuspendCount = $shouldSuspend->count();
        $shouldActivateCount = $shouldActivate->count();

        if ($shouldSuspendCount) {
            $shouldSuspend->each->updateQuietly(['status' => EmployeeStatuses::Suspended]);
            $this->info("{$shouldSuspendCount} employees suspended!");
        }

        if ($shouldActivateCount) {
            $shouldActivate->each->updateQuietly(['status' => EmployeeStatuses::Hired]);
            $this->info("{$shouldActivateCount} suspended employees activated!");
        }

        if ($shouldActivateCount === 0 && $shouldActivateCount === 0) {
            $this->warn('No status change needed for employees!');
        }

        return 0;
    }
}
