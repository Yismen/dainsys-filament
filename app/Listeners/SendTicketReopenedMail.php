<?php

namespace App\Listeners;

use App\Events\TicketReopenedEvent;
use App\Mail\TicketReopenedMail;
use Illuminate\Support\Facades\Mail;

class SendTicketReopenedMail
{
    public function handle(TicketReopenedEvent $event)
    {
        Mail::send(new TicketReopenedMail($event->ticket));
    }
}
