<?php

namespace App\Mail;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Services\MailingService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmployeeCreated extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public Employee $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function build()
    {
        return $this
            ->to(MailingService::subscribers($this))
            ->markdown('mail.employee-created');
    }
}
