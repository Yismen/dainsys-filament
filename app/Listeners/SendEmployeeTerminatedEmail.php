<?php

namespace App\Listeners;

use App\Events\TerminationCreated;
use App\Mail\TerminationCreated as MailTerminationCreated;
use Illuminate\Support\Facades\Mail;

class SendEmployeeTerminatedEmail
{
    public function handle(TerminationCreated $event)
    {
        Mail::send(new MailTerminationCreated($event->termination));
    }
}
