<?php

namespace App\Notifications\Employees;

use App\Models\Employee;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeReactivatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Employee $employee) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('employees.reactivated');
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Employee Reactivated')
            ->line("{$this->employee->full_name} has been reactivated.");
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Employee Reactivated',
            'body' => "{$this->employee->full_name} has been reactivated.",
            'format' => 'filament',
            'duration' => 'persistent',
            'employee_id' => $this->employee->id,
        ];
    }
}
