<?php

namespace App\Listeners;

use App\Events\TicketCompletedEvent;
use App\Mail\TicketCompletedMail;
use App\Models\Ticket;
use App\Services\RecipientsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class SendTicketCompletedMail
{
    protected Ticket $ticket;

    protected string $comment;

    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService;
    }

    public function handle(TicketCompletedEvent $event)
    {
        $this->ticket = $event->ticket;
        $this->comment = $event->comment;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new TicketCompletedMail($this->ticket, $this->comment));
        }
    }

    protected function recipients(): Collection
    {
        return $this->recipientsService
            ->ofTicket($this->ticket)
            ->superAdmins()
            ->owner()
            ->agent()
            ->departmentAdmins()
            ->get();
    }
}
