<?php

namespace App\Listeners;

use App\Events\TicketCreatedEvent;
use App\Mail\TicketCreatedMail;
use Illuminate\Support\Facades\Mail;

class SendTicketCreatedMail
{
    public function handle(TicketCreatedEvent $event)
    {
        Mail::send(new TicketCreatedMail($event->ticket));
    }
}
