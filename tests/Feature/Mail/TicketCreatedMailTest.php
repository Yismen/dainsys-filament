<?php

use App\Events\TicketCreatedEvent;
use App\Mail\TicketCreatedMail;
use App\Models\Ticket;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Mail::fake();
    Event::fake([
        TicketCreatedEvent::class,
    ]);
});

it('renders correctly', function (): void {

    $ticket = Ticket::factory()->create();

    $mailable = new TicketCreatedMail($ticket);

    $mailable->assertHasSubject("Ticket #{$ticket->reference} Created");
});
