<?php

namespace App\Mail;

use App\Models\Employee;
use App\Services\MailingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeReactivatedMail extends Mailable implements ShouldQueue
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
            ->markdown('mail.employee-reactivated');
    }
}
