<?php

namespace App\Listeners;

use App\Events\TicketAssignedEvent;
use App\Notifications\Tickets\TicketAssignedNotification;
use App\Services\TicketRecipientsService;
use Illuminate\Support\Facades\Notification;

class SendTicketAssignedMail
{
    public function handle(TicketAssignedEvent $event): void
    {
        $recipients = (new TicketRecipientsService)
            ->ofTicket($event->ticket)
            ->superAdmins()
            ->owner()
            ->agent()
            ->supportManagers()
            ->get(false);

        Notification::send($recipients, new TicketAssignedNotification($event->ticket));
    }
}
