<?php

namespace App\Mail;

use App\Models\TicketReply;
use App\Services\TicketRecipientsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
            ->to(
                (new TicketRecipientsService)
                    ->ofTicket($ticket)
                    ->owner()
                    ->operator()
                    ->ticketAdmins()
                    ->get()
            )
            ->subject("Ticket #{$ticket->reference} Has Been Replied")
            ->priority($ticket->mail_priority)
            ->markdown('mail.support.reply-created', [
                'user' => $this->reply?->user,
            ]);
    }
}
