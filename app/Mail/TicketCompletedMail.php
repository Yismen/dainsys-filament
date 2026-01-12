<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Services\TicketRecipientsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketCompletedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public Ticket $ticket;

    public $comment;

    public function __construct(Ticket $ticket, string $comment = '')
    {
        $this->ticket = $ticket;
        $this->comment = $comment;
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
            ->subject("Ticket #{$this->ticket->reference} Completed")
            ->priority($this->ticket->mail_priority)
            ->markdown('mail.support.ticket-completed', [
                'user' => $this->ticket->owner,
                // 'user' => $this->ticket->audits()->latest()->first()?->user
            ]);
    }
}
