<?php

namespace App\Listeners;

use App\Events\EmployeeTerminatedEvent;
use App\Mail\EmployeeTerminatedMail;
use App\Notifications\Employees\EmployeeTerminatedNotification;
use App\Services\MailingService;
use Illuminate\Support\Facades\Notification;

class SendEmployeeTerminatedEmail
{
    public function handle(EmployeeTerminatedEvent $event): void
    {
        $recipients = MailingService::subscribers(EmployeeTerminatedMail::class);

        Notification::send($recipients, new EmployeeTerminatedNotification($event->termination));
    }
}
