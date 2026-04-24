<?php

namespace App\Listeners;

use App\Events\TicketReopenedEvent;
use App\Notifications\Tickets\TicketReopenedNotification;
use App\Services\TicketRecipientsService;
use Illuminate\Support\Facades\Notification;

class SendTicketReopenedMail
{
    public function handle(TicketReopenedEvent $event): void
    {
        $recipients = (new TicketRecipientsService)
            ->ofTicket($event->ticket)
            ->superAdmins()
            ->owner()
            ->supportAgents()
            ->supportManagers()
            ->get(false);

        Notification::send($recipients, new TicketReopenedNotification($event->ticket));
    }
}
