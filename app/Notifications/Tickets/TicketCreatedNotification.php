<?php

namespace App\Notifications\Tickets;

use App\Models\Ticket;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('tickets.created');
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Ticket #{$this->ticket->reference} Created")
            ->line("A new support ticket was created: #{$this->ticket->reference}.")
            ->line("Subject: {$this->ticket->subject}");
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Ticket Created',
            'body' => "Ticket #{$this->ticket->reference} was created.",
            'format' => 'filament',
            'duration' => 'persistent',
            'ticket_id' => $this->ticket->id,
            'reference' => $this->ticket->reference,
            'subject' => $this->ticket->subject,
            'status' => $this->ticket->status?->value,
        ];
    }
}
