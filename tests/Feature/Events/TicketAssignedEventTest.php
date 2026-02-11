<?php

use App\Events\TicketAssignedEvent;
use App\Events\TicketCreatedEvent;
use App\Listeners\SendTicketAssignedMail;
use App\Mail\TicketAssignedMail;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

test('event is dispatched when ticket is assigned', function (): void {
    Event::fake([
        TicketAssignedEvent::class,
        TicketCreatedEvent::class,
    ]);

    $ticket = Ticket::factory()->create();

    $ticket->assignTo(User::factory()->create());

    Event::assertDispatched(TicketAssignedEvent::class);
    Event::assertListening(
        TicketAssignedEvent::class,
        SendTicketAssignedMail::class
    );
});

test('event is dispatched is grabbed', function (): void {
    Event::fake([
        TicketAssignedEvent::class,
        TicketCreatedEvent::class,
    ]);

    $this->actingAs(User::factory()->create());

    $ticket = Ticket::factory()->create();

    $ticket->grab();

    Event::assertDispatched(TicketAssignedEvent::class);
});

test('when ticket is created an email is sent', function (): void {
    Mail::fake();

    $ticket = Ticket::factory()->create();
    $ticket->assignTo(User::factory()->create());

    Mail::assertQueued(TicketAssignedMail::class);
});
