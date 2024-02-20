<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Events\EmployeeReactivated;
use App\Mail\EmployeeReactivated as MailEmployeeReactivated;

class SendEmployeeReactivatedEmail
{
    public function handle(EmployeeReactivated $event)
    {
        Mail::send(new MailEmployeeReactivated($event->employee));
    }
}
