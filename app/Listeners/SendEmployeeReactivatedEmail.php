<?php

namespace App\Listeners;

use App\Events\EmployeeReactivatedEvent;
use App\Mail\EmployeeReactivated as MailEmployeeReactivated;
use Illuminate\Support\Facades\Mail;

class SendEmployeeReactivatedEmail
{
    public function handle(EmployeeReactivatedEvent $event)
    {
        Mail::send(new MailEmployeeReactivated($event->employee));
    }
}
