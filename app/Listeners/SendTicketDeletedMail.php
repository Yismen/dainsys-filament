<?php

namespace App\Listeners;

use App\Events\TicketDeletedEvent;
use App\Mail\TicketDeletedMail;
use Illuminate\Support\Facades\Mail;

class SendTicketDeletedMail
{
    public function handle(TicketDeletedEvent $event)
    {
        Mail::send(new TicketDeletedMail($event->ticket));
    }
}
