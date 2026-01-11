<?php

namespace App\Listeners;

use App\Events\EmployeeSuspendedEvent;
use App\Mail\EmployeeSuspendedMail;
use Illuminate\Support\Facades\Mail;

class SendEmployeeSuspendedEmail
{
    public function handle(EmployeeSuspendedEvent $event)
    {
        Mail::send(new EmployeeSuspendedMail($event->suspension));
    }
}
