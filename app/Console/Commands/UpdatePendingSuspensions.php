<?php

namespace App\Console\Commands;

use App\Enums\EmployeeStatuses;
use App\Enums\SuspensionStatuses;
use App\Models\Suspension;
use Illuminate\Console\Command;

class UpdatePendingSuspensions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:update-pending-suspensions';

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
        $suspensions = Suspension::query()
            ->with('employee')
            ->whereHas('employee', function ($employeeQuery) {
                $employeeQuery->where('status', '<>', EmployeeStatuses::Terminated);
            })
            ->where(function ($query) {
                $query->where('status', SuspensionStatuses::Current)
                    ->orWhere('status', SuspensionStatuses::Pending);
            })
            ->get()
            ->each(fn (Suspension $suspension) => $suspension->touch());

        $this->info("A total of {$suspensions->count()} were updated");

        return 0;
    }
}
