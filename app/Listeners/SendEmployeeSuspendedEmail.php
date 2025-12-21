<?php

namespace App\Listeners;

use App\Events\SuspensionUpdated;
use App\Mail\SuspensionUpdated as MailSuspensionUpdated;
use Illuminate\Support\Facades\Mail;

class SendEmployeeSuspendedEmail
{
    public function handle(SuspensionUpdated $event)
    {
        Mail::send(new MailSuspensionUpdated($event->suspension));
    }
}
