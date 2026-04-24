<?php

namespace App\Listeners;

use App\Events\TicketDeletedEvent;
use App\Notifications\Tickets\TicketDeletedNotification;
use App\Services\TicketRecipientsService;
use Illuminate\Support\Facades\Notification;

class SendTicketDeletedMail
{
    public function handle(TicketDeletedEvent $event): void
    {
        $recipients = (new TicketRecipientsService)
            ->ofTicket($event->ticket)
            ->superAdmins()
            ->owner()
            ->agent()
            ->supportManagers()
            ->get(false);

        Notification::send($recipients, new TicketDeletedNotification($event->ticket));
    }
}
