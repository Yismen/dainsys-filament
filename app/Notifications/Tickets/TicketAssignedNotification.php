<?php

namespace App\Notifications\Tickets;

use App\Models\Ticket;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('tickets.assigned');
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Ticket #{$this->ticket->reference} Assigned")
            ->line("Ticket #{$this->ticket->reference} has been assigned.");
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Ticket Assigned',
            'body' => "Ticket #{$this->ticket->reference} has been assigned.",
            'format' => 'filament',
            'duration' => 'persistent',
            'ticket_id' => $this->ticket->id,
            'reference' => $this->ticket->reference,
            'assigned_to' => $this->ticket->assigned_to,
            'status' => $this->ticket->status?->value,
        ];
    }
}
