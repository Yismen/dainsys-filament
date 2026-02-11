<?php

use App\Events\TicketCreatedEvent;
use App\Events\TicketDeletedEvent;
use App\Mail\TicketDeletedMail;
use App\Models\Ticket;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Mail::fake();
    Event::fake([
        TicketCreatedEvent::class,
        TicketDeletedEvent::class,
    ]);
});

it('renders correctly', function (): void {

    $ticket = Ticket::factory()->create();

    $mailable = new TicketDeletedMail($ticket);

    $mailable->assertHasSubject("Ticket #{$ticket->reference} Deleted");
});
