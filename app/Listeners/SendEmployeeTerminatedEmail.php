<?php

namespace App\Listeners;

use App\Events\TerminationCreatedEvent;
use App\Mail\MailTerminationCreatedMail;
use Illuminate\Support\Facades\Mail;

class SendEmployeeTerminatedEmail
{
    public function handle(TerminationCreatedEvent $event)
    {
        Mail::send(new MailTerminationCreatedMail($event->termination));
    }
}
