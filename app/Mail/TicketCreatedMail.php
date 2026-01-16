<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Services\TicketRecipientsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        return $this
            ->to(
                (new TicketRecipientsService)
                    ->ofTicket($this->ticket)
                    ->owner()
                    ->superAdmins()
                    ->ticketAdmins()
                    ->ticketOperators()
                    ->get()
            )
            ->subject("Ticket #{$this->ticket->reference} Created")
            ->priority($this->ticket->mail_priority)
            ->markdown('mail.support.ticket-created');
    }
}
