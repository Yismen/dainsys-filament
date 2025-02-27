<?php

namespace App\Listeners;

use App\Models\TicketReply;
use App\Mail\ReplyCreatedMail;
use App\Services\RecipientsService;
use App\Mail\TicketReplyCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Events\TicketReplyCreatedEvent;

class SendTicketReplyCreatedMail
{
    protected TicketReply $reply;
    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService();
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
