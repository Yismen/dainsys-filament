<?php

namespace App\Notifications\Reports;

use App\Mail\SuspendedEmployeesMail;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class SuspendedEmployeesReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Collection $employees) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('reports.suspended_employees');
    }

    public function toMail(object $notifiable): SuspendedEmployeesMail
    {
        return new SuspendedEmployeesMail($this->employees);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Suspended Employees Report',
            'body' => "There are {$this->employees->count()} employees currently suspended.",
            'format' => 'filament',
            'duration' => 'persistent',
            'count' => $this->employees->count(),
        ];
    }
}
