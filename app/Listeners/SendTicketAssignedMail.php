<?php

namespace App\Listeners;

use App\Events\TicketAssignedEvent;
use App\Mail\TicketAssignedMail;
use Illuminate\Support\Facades\Mail;

class SendTicketAssignedMail
{
    public function handle(TicketAssignedEvent $event)
    {
        Mail::send(new TicketAssignedMail($event->ticket));
    }
}
