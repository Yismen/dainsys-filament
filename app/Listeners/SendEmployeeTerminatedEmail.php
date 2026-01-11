<?php

namespace App\Listeners;

use App\Events\EmployeeTerminatedEvent;
use App\Mail\EmployeeTerminatedMail;
use Illuminate\Support\Facades\Mail;

class SendEmployeeTerminatedEmail
{
    public function handle(EmployeeTerminatedEvent $event)
    {
        Mail::send(new EmployeeTerminatedMail($event->termination));
    }
}
