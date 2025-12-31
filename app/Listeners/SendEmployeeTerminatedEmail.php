<?php

namespace App\Listeners;

use App\Events\TerminationCreatedEvent;
use App\Mail\TerminationCreated as MailTerminationCreated;
use Illuminate\Support\Facades\Mail;

class SendEmployeeTerminatedEmail
{
    public function handle(TerminationCreatedEvent $event)
    {
        Mail::send(new MailTerminationCreated($event->termination));
    }
}
