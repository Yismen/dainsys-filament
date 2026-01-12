<?php

namespace App\Listeners;

use App\Events\TicketReplyCreatedEvent;
use App\Mail\TicketReplyCreatedMail;
use App\Models\TicketReply;
use App\Services\TicketRecipientsService;
use Illuminate\Support\Facades\Mail;

class SendTicketReplyCreatedMail
{
    public function handle(TicketReplyCreatedEvent $event)
    {
        Mail::send(new TicketReplyCreatedMail($event->reply));
    }

}
