<?php

namespace App\Listeners;

use App\Events\EmployeeReactivatedEvent;
use App\Mail\EmployeeReactivatedMail;
use App\Notifications\Employees\EmployeeReactivatedNotification;
use App\Services\MailingService;
use Illuminate\Support\Facades\Notification;

class SendEmployeeReactivatedEmail
{
    public function handle(EmployeeReactivatedEvent $event): void
    {
        $recipients = MailingService::subscribers(EmployeeReactivatedMail::class);

        Notification::send($recipients, new EmployeeReactivatedNotification($event->employee));
    }
}
