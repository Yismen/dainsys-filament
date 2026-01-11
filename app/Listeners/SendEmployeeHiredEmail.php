<?php

namespace App\Listeners;

use App\Events\EmployeeHiredEvent;
use App\Mail\EmployeeHiredMail;
use Illuminate\Support\Facades\Mail;

class SendEmployeeHiredEmail
{
    public function handle(EmployeeHiredEvent $event)
    {
        Mail::send(new EmployeeHiredMail($event->hire->employee));
    }
}
