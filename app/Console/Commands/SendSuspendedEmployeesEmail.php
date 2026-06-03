<?php

namespace App\Console\Commands;

use App\Enums\EmployeeStatuses;
use App\Mail\SuspendedEmployeesMail;
use App\Models\Employee;
use App\Notifications\Reports\SuspendedEmployeesReportNotification;
use App\Services\MailingService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

#[Description('Send a daily report with the employees in status suspended, with the start and end date.')]
#[Signature('dainsys:send-suspended-employees-email')]
class SendSuspendedEmployeesEmail extends Command
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
        $this->call(UpdateEmployeeSuspensions::class);

        $employees = Employee::query()
            ->with([
                'site',
                'project',
                'suspensions' => fn ($query) => $query->active(),
            ])
            ->where('status', EmployeeStatuses::Suspended)
            ->whereHas('suspensions', function ($query): void {
                $query->active();
            })
            ->get();

        if ($employees->count() > 0) {
            $recipients = MailingService::subscribers(SuspendedEmployeesMail::class);
            Notification::send($recipients, new SuspendedEmployeesReportNotification($employees));
            $this->info('Employees suspended report sent');
        } else {
            $this->warn('Nothing to send!');
        }

        return 0;
    }
}
