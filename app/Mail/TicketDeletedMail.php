<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketDeletedMail extends Mailable implements ShouldQueue
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
            ->subject("Ticket #{$this->ticket->reference} Deleted")
            ->priority($this->ticket->mail_priority)
            ->markdown('mail.support.ticket-deleted', [
                'user' => $this->ticket->owner
                // 'user' => $this->ticket->audits()->latest()->first()?->user
            ])

        ;
    }
}
