<?php

namespace App\Listeners;

use App\Events\EmployeeHiredEvent;
use App\Mail\EmployeeHiredMail;
use App\Notifications\Employees\EmployeeHiredNotification;
use App\Services\MailingService;
use Illuminate\Support\Facades\Notification;

class SendEmployeeHiredEmail
{
    public function handle(EmployeeHiredEvent $event): void
    {
        $recipients = MailingService::subscribers(EmployeeHiredMail::class);

        Notification::send($recipients, new EmployeeHiredNotification($event->hire));
    }
}
