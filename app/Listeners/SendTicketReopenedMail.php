<?php

namespace App\Listeners;

use App\Events\TicketReopenedEvent;
use App\Mail\TicketReopenedMail;
use App\Models\Ticket;
use App\Services\TicketRecipientsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class SendTicketReopenedMail
{
    public function handle(TicketReopenedEvent $event)
    {
        Mail::send(new TicketReopenedMail($event->ticket));
    }
}
