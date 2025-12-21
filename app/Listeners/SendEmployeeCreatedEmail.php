<?php

namespace App\Listeners;

use App\Events\EmployeeHiredEvent;
use App\Mail\EmployeeCreated as MailEmployeeCreated;
use Illuminate\Support\Facades\Mail;

class SendEmployeeCreatedEmail
{
    public function handle(EmployeeHiredEvent $event)
    {
        Mail::send(new MailEmployeeCreated($event->hire->employee));
    }
}
