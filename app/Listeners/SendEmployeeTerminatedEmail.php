<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Events\TerminationCreated;
use App\Mail\TerminationCreated as MailTerminationCreated;

class SendEmployeeTerminatedEmail
{
    public function handle(TerminationCreated $event)
    {
        Mail::send(new MailTerminationCreated($event->termination));
    }
}
