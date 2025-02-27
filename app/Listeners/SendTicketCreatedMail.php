<?php

namespace App\Listeners;

use App\Models\Ticket;
use App\Mail\TicketCreatedMail;
use App\Events\TicketCreatedEvent;
use App\Services\RecipientsService;
use Illuminate\Support\Facades\Mail;

class SendTicketCreatedMail
{
    protected Ticket $ticket;
    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService();
    }

    public function handle(TicketCreatedEvent $event)
    {
        $this->ticket = $event->ticket;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new TicketCreatedMail($this->ticket));
        }
    }

    protected function recipients()
    {
        return $this->recipientsService
            ->ofTicket($this->ticket)
            ->superAdmins()
            ->owner()
            ->departmentAdmins()
            ->departmentAgents()
            ->get();
    }
}
