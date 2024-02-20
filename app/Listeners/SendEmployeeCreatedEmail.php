<?php

namespace App\Listeners;

use App\Events\EmployeeCreated;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeCreated as MailEmployeeCreated;

class SendEmployeeCreatedEmail
{
    public function handle(EmployeeCreated $event)
    {
        Mail::send(new MailEmployeeCreated($event->employee));
    }
}
