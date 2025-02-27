<?php

namespace App\Listeners;

use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketDeletedMail;
use Illuminate\Database\Eloquent\Collection;
use App\Events\TicketDeletedEvent;
use App\Services\RecipientsService;

class SendTicketDeletedMail
{
    protected Ticket $ticket;
    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService();
    }

    public function handle(TicketDeletedEvent $event)
    {
        $this->ticket = $event->ticket;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new TicketDeletedMail($this->ticket));
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
