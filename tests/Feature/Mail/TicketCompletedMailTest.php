<?php

use App\Events\TicketCompletedEvent;
use App\Events\TicketCreatedEvent;
use App\Mail\TicketCompletedMail;
use App\Models\Ticket;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Mail::fake();
    Event::fake([
        TicketCreatedEvent::class,
        TicketCompletedEvent::class,
    ]);
});

it('renders correctly', function (): void {

    $ticket = Ticket::factory()->create();

    $mailable = new TicketCompletedMail($ticket);

    $mailable->assertHasSubject("Ticket #{$ticket->reference} Completed");
});
