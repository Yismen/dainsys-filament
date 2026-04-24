<?php

namespace App\Listeners;

use App\Events\TicketCreatedEvent;
use App\Notifications\Tickets\TicketCreatedNotification;
use App\Services\TicketRecipientsService;
use Illuminate\Support\Facades\Notification;

class SendTicketCreatedMail
{
    public function handle(TicketCreatedEvent $event): void
    {
        $recipients = (new TicketRecipientsService)
            ->ofTicket($event->ticket)
            ->owner()
            ->superAdmins()
            ->supportManagers()
            ->supportAgents()
            ->get(false);

        Notification::send($recipients, new TicketCreatedNotification($event->ticket));
    }
}
