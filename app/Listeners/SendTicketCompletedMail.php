<?php

namespace App\Listeners;

use App\Events\TicketCompletedEvent;
use App\Mail\TicketCompletedMail;
use App\Models\Ticket;
use App\Services\TicketRecipientsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class SendTicketCompletedMail
{
    public function handle(TicketCompletedEvent $event)
    {
        Mail::send(new TicketCompletedMail($event->ticket, $event->comment));
    }
}
