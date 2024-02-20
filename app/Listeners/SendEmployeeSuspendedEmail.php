<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Events\SuspensionUpdated;
use App\Mail\SuspensionUpdated as MailSuspensionUpdated;

class SendEmployeeSuspendedEmail
{
    public function handle(SuspensionUpdated $event)
    {
        Mail::send(new MailSuspensionUpdated($event->suspension));
    }
}
