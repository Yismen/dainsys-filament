<?php

namespace App\Listeners;

use App\Events\TicketCompletedEvent;
use App\Mail\TicketCompletedMail;
use Illuminate\Support\Facades\Mail;

class SendTicketCompletedMail
{
    public function handle(TicketCompletedEvent $event)
    {
        Mail::send(new TicketCompletedMail($event->ticket, $event->comment));
    }
}
