<?php

namespace App\Notifications\Employees;

use App\Models\Suspension;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeSuspendedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Suspension $suspension) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('employees.suspended');
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Employee Suspended')
            ->line("{$this->suspension->employee->full_name} has been suspended.");
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Employee Suspended',
            'body' => "{$this->suspension->employee->full_name} has been suspended.",
            'format' => 'filament',
            'duration' => 'persistent',
            'suspension_id' => $this->suspension->id,
            'employee_id' => $this->suspension->employee_id,
        ];
    }
}
