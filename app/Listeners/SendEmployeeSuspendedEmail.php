<?php

namespace App\Listeners;

use App\Events\EmployeeSuspendedEvent;
use App\Mail\EmployeeSuspendedMail;
use App\Notifications\Employees\EmployeeSuspendedNotification;
use App\Services\MailingService;
use Illuminate\Support\Facades\Notification;

class SendEmployeeSuspendedEmail
{
    public function handle(EmployeeSuspendedEvent $event): void
    {
        $recipients = MailingService::subscribers(EmployeeSuspendedMail::class);

        Notification::send($recipients, new EmployeeSuspendedNotification($event->suspension));
    }
}
