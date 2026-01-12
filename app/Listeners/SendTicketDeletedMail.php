<?php

namespace App\Listeners;

use App\Events\TicketDeletedEvent;
use App\Mail\TicketDeletedMail;
use App\Models\Ticket;
use App\Services\TicketRecipientsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class SendTicketDeletedMail
{
    public function handle(TicketDeletedEvent $event)
    {
        Mail::send(new TicketDeletedMail($event->ticket));
    }
}
