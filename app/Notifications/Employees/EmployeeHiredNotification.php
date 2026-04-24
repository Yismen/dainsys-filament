<?php

namespace App\Notifications\Employees;

use App\Models\Hire;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeHiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Hire $hire) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('employees.hired');
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Employee Hired')
            ->line("{$this->hire->employee->full_name} has been hired.");
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Employee Hired',
            'body' => "{$this->hire->employee->full_name} has been hired.",
            'format' => 'filament',
            'duration' => 'persistent',
            'hire_id' => $this->hire->id,
            'employee_id' => $this->hire->employee_id,
        ];
    }
}
