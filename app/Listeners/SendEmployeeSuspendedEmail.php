<?php

namespace App\Listeners;

use App\Events\SuspensionUpdatedEvent;
use App\Mail\SuspensionUpdatedMail;
use Illuminate\Support\Facades\Mail;

class SendEmployeeSuspendedEmail
{
    public function handle(SuspensionUpdatedEvent $event)
    {
        Mail::send(new SuspensionUpdatedMail($event->suspension));
    }
}
