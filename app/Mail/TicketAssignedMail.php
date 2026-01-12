<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Services\TicketRecipientsService;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketAssignedMail extends Mailable implements ShouldQueue
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
            (new TicketRecipientsService())
                ->ofTicket($this->ticket)
                ->superAdmins()
                ->owner()
                ->operator()
                ->ticketAdmins()
                ->get()
            )
            ->subject("Ticket #{$this->ticket->reference} Assigned")
            ->priority($this->ticket->mail_priority)
            ->markdown('mail.support.ticket-assigned', [
                'user' => $this->ticket->owner,
                // 'user' => $this->ticket->audits()->latest()->first()?->user
            ]);
    }
}
