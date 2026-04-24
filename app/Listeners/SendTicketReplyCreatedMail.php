<?php

namespace App\Listeners;

use App\Events\TicketReplyCreatedEvent;
use App\Notifications\Tickets\TicketReplyCreatedNotification;
use App\Services\TicketRecipientsService;
use Illuminate\Support\Facades\Notification;

class SendTicketReplyCreatedMail
{
    public function handle(TicketReplyCreatedEvent $event): void
    {
        $ticket = $event->reply->ticket;

        $recipients = (new TicketRecipientsService)
            ->ofTicket($ticket)
            ->owner()
            ->agent()
            ->supportManagers()
            ->get(false);

        Notification::send($recipients, new TicketReplyCreatedNotification($event->reply));
    }
}
