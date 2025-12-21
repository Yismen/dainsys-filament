<?php

namespace App\Listeners;

use App\Events\EmployeeReactivated;
use App\Mail\EmployeeReactivated as MailEmployeeReactivated;
use Illuminate\Support\Facades\Mail;

class SendEmployeeReactivatedEmail
{
    public function handle(EmployeeReactivated $event)
    {
        Mail::send(new MailEmployeeReactivated($event->employee));
    }
}
