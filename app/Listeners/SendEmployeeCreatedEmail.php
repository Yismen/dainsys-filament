<?php

namespace App\Listeners;

use App\Events\EmployeeHiredEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeCreated as MailEmployeeCreated;

class SendEmployeeCreatedEmail
{
    public function handle(EmployeeHiredEvent $event)
    {
        Mail::send(new MailEmployeeCreated($event->employee));
    }
}
