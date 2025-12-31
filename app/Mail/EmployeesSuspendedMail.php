<?php

namespace App\Mail;

use App\Services\MailingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeesSuspendedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    public function build()
    {
        return $this
            ->to(MailingService::subscribers($this))
            ->markdown('mail.employees-suspended');
    }
}
