<?php

use App\Events\TicketCreatedEvent;
use App\Events\TicketReplyCreatedEvent;
use App\Mail\TicketAssignedMail;
use App\Models\Ticket;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Mail::fake();
    Event::fake([
        TicketCreatedEvent::class,
        TicketReplyCreatedEvent::class,
    ]);
});

it('renders correctly', function (): void {

    $ticket = Ticket::factory()->create();

    $mailable = new TicketAssignedMail($ticket);

    $mailable->assertHasSubject("Ticket #{$ticket->reference} Assigned");
});
