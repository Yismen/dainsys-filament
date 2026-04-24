<?php

namespace App\Listeners;

use App\Events\TicketCompletedEvent;
use App\Notifications\Tickets\TicketCompletedNotification;
use App\Services\TicketRecipientsService;
use Illuminate\Support\Facades\Notification;

class SendTicketCompletedMail
{
    public function handle(TicketCompletedEvent $event): void
    {
        $recipients = (new TicketRecipientsService)
            ->ofTicket($event->ticket)
            ->superAdmins()
            ->owner()
            ->agent()
            ->supportManagers()
            ->get(false);

        Notification::send($recipients, new TicketCompletedNotification($event->ticket, $event->comment));
    }
}
