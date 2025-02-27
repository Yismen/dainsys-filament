<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\TicketReply;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketReplyCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public TicketReply $reply;

    public function __construct(TicketReply $reply)
    {
        $this->reply = $reply;
    }

    public function build()
    {
        $ticket = $this->reply->ticket;

        return $this
            ->subject("Ticket #{$ticket->reference} Has Been Replied")
            ->priority($ticket->mail_priority)
            ->markdown('mail.support.reply-created', [
                'user' => $this->reply?->user
            ])

        ;
    }
}
