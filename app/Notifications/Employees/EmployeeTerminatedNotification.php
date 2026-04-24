<?php

namespace App\Notifications\Employees;

use App\Models\Termination;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeTerminatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Termination $termination) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('employees.terminated');
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Employee Terminated')
            ->line("{$this->termination->employee->full_name} has been terminated.");
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Employee Terminated',
            'body' => "{$this->termination->employee->full_name} has been terminated.",
            'format' => 'filament',
            'duration' => 'persistent',
            'termination_id' => $this->termination->id,
            'employee_id' => $this->termination->employee_id,
        ];
    }
}
