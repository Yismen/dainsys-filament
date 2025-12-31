<?php

namespace App\Listeners;

use App\Events\EmployeeHiredEvent;
use App\Mail\EmployeeCreatedMail;
use Illuminate\Support\Facades\Mail;

class SendEmployeeCreatedEmail
{
    public function handle(EmployeeHiredEvent $event)
    {
        Mail::send(new EmployeeCreatedMail($event->hire->employee));
    }
}
