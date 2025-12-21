<?php

namespace App\Listeners;

use App\Events\TicketReplyCreatedEvent;
use App\Mail\TicketReplyCreatedMail;
use App\Models\TicketReply;
use App\Services\RecipientsService;
use Illuminate\Support\Facades\Mail;

class SendTicketReplyCreatedMail
{
    protected TicketReply $reply;

    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService;
    }

    public function handle(TicketReplyCreatedEvent $event)
    {
        $this->reply = $event->reply;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new TicketReplyCreatedMail($this->reply));
        }
    }

    protected function recipients()
    {
        return $this->recipientsService
            ->ofTicket($this->reply->ticket)
            // ->superAdmins()
            ->owner()
            ->agent()
            ->departmentAdmins()
            ->get();
    }
}
