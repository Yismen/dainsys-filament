<?php

namespace App\Listeners;

use App\Events\SuspensionUpdatedEvent;
use App\Mail\SuspensionUpdated as MailSuspensionUpdated;
use Illuminate\Support\Facades\Mail;

class SendEmployeeSuspendedEmail
{
    public function handle(SuspensionUpdatedEvent $event)
    {
        Mail::send(new MailSuspensionUpdated($event->suspension));
    }
}
