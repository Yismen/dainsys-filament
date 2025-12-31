<?php

namespace App\Console\Commands;

use App\Enums\EmployeeStatuses;
use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmployeesSuspended extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dainsys:employees-suspended';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily report with the employees in status suspended, with the start and end date.';

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
        $this->call(UpdateEmployeeSuspensions::class);

        $employees = Employee::query()
            ->with([
                'site',
                'project',
                'suspensions' => fn ($query) => $query->active(),
            ])
            ->where('status', EmployeeStatuses::Suspended)
            ->whereHas('suspensions', function ($query) {
                $query->active();
            })
            ->get();

        if ($employees->count() > 0) {
            Mail::send(new \App\Mail\EmployeesSuspendedMail($employees));
            $this->info('Employees suspended report sent');
        } else {
            $this->warn('Nothing to send!');
        }

        return 0;
    }
}
