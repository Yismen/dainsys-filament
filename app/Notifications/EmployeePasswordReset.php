<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class EmployeePasswordReset extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Employee $employee) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Employee Password Reset',
            'message' => "The password for {$this->employee->full_name} has been reset.",
            'employee_id' => $this->employee->id,
            'employee_name' => $this->employee->full_name,
        ];
    }
}
