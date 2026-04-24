<?php

namespace App\Notifications\Tickets;

use App\Models\Ticket;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketReopenedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('tickets.reopened');
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Ticket #{$this->ticket->reference} Reopened")
            ->line("Ticket #{$this->ticket->reference} has been reopened.");
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Ticket Reopened',
            'body' => "Ticket #{$this->ticket->reference} has been reopened.",
            'format' => 'filament',
            'duration' => 'persistent',
            'ticket_id' => $this->ticket->id,
            'reference' => $this->ticket->reference,
            'status' => $this->ticket->status?->value,
        ];
    }
}
