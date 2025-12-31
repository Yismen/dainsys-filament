<?php

namespace App\Listeners;

use App\Events\EmployeeReactivatedEvent;
use App\Mail\EmployeeReactivatedMail;
use Illuminate\Support\Facades\Mail;

class SendEmployeeReactivatedEmail
{
    public function handle(EmployeeReactivatedEvent $event)
    {
        Mail::send(new EmployeeReactivatedMail($event->employee));
    }
}
