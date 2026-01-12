<?php

use App\Events\TicketCreatedEvent;
use App\Events\TicketReplyCreatedEvent;
use App\Mail\TicketReplyCreatedMail;
use App\Models\TicketReply;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    Event::fake([
        TicketCreatedEvent::class,
        TicketReplyCreatedEvent::class,
    ]);
});

it('renders correctly', function () {

    $ticket_reply = TicketReply::factory()->create();

    $mailable = new TicketReplyCreatedMail($ticket_reply);

    $mailable->assertHasSubject("Ticket #{$ticket_reply->ticket->reference} Has Been Replied");
});
