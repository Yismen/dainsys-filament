<?php

namespace App\Notifications\Tickets;

use App\Models\TicketReply;
use App\Services\NotificationChannelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketReplyCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public TicketReply $reply) {}

    public function via(object $notifiable): array
    {
        return app(NotificationChannelResolver::class)->resolve('tickets.reply_created');
    }

    public function toMail(object $notifiable): MailMessage
    {
        $ticket = $this->reply->ticket;

        return (new MailMessage)
            ->subject("{$this->reply->author->name} Replied on Ticket #{$ticket->reference}")
            ->line("A new reply was added to ticket #{$ticket->reference}.");
    }

    public function toDatabase(object $notifiable): array
    {
        $ticket = $this->reply->ticket;

        return [
            'title' => 'Ticket Reply Added',
            'body' => "{$this->reply->author->name} replied on ticket #{$ticket->reference}.",
            'format' => 'filament',
            'duration' => 'persistent',
            'ticket_id' => $ticket->id,
            'ticket_reply_id' => $this->reply->id,
            'reference' => $ticket->reference,
        ];
    }
}
