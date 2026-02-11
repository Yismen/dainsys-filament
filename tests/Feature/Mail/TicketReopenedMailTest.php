<?php

use App\Events\TicketCreatedEvent;
use App\Events\TicketReopenedEvent;
use App\Mail\TicketReopenedMail;
use App\Models\Ticket;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Mail::fake();
    Event::fake([
        TicketCreatedEvent::class,
        TicketReopenedEvent::class,
    ]);
});

it('renders correctly', function (): void {

    $ticket = Ticket::factory()->create();

    $mailable = new TicketReopenedMail($ticket);

    $mailable->assertHasSubject("Ticket #{$ticket->reference} Reopened");
});
