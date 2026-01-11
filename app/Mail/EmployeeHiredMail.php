<?php

namespace App\Mail;

use App\Models\Hire;
use App\Services\MailingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeHiredMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Hire $hire) {}

    public function build()
    {
        return $this
            ->to(MailingService::subscribers($this))
            ->markdown('mail.employee-hired');
    }
}
