<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Services\MailingService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmployeesSuspended extends Mailable implements ShouldQueue
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
