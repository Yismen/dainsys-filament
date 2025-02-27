<?php

namespace App\Listeners;

use App\Models\Ticket;
use App\Mail\TicketAssignedMail;
use App\Events\TicketAssignedEvent;
use App\Services\RecipientsService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Collection;

class SendTicketAssignedMail
{
    protected Ticket $ticket;
    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService();
    }

    public function handle(TicketAssignedEvent $event)
    {
        $this->ticket = $event->ticket;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new TicketAssignedMail($this->ticket));
        }
    }

    protected function recipients(): Collection
    {
        return  $this->recipientsService
            ->ofTicket($this->ticket)
            ->superAdmins()
            ->owner()
            ->agent()
            ->departmentAdmins()
            ->get();
    }
}
