<?php

namespace App\Notifications\Tickets;

use App\Models\Ticket;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ticket $ticket,
        public string $comment = ''
    ) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('tickets.completed');
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Ticket #{$this->ticket->reference} Completed")
            ->line("Ticket #{$this->ticket->reference} has been completed.")
            ->line($this->comment !== '' ? "Comment: {$this->comment}" : 'No completion comment was provided.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Ticket Completed',
            'body' => "Ticket #{$this->ticket->reference} has been completed.",
            'format' => 'filament',
            'duration' => 'persistent',
            'ticket_id' => $this->ticket->id,
            'reference' => $this->ticket->reference,
            'comment' => $this->comment,
            'status' => $this->ticket->status?->value,
        ];
    }
}
