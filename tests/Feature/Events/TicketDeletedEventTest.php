<?php

use App\Events\TicketCreatedEvent;
use App\Events\TicketDeletedEvent;
use App\Listeners\SendTicketDeletedMail;
use App\Mail\TicketDeletedMail;
use App\Models\Ticket;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

test('event is dispatched', function () {
    Event::fake([
        TicketDeletedEvent::class,
        TicketCreatedEvent::class,
    ]);

    $ticket = Ticket::factory()->create();

    $ticket->delete();

    Event::assertDispatched(TicketDeletedEvent::class);
    Event::assertListening(
        TicketDeletedEvent::class,
        SendTicketDeletedMail::class
    );
});

test('when ticket is created an email is sent', function () {
    Mail::fake();

    $ticket = Ticket::factory()->create();
    $ticket->delete();

    Mail::assertQueued(TicketDeletedMail::class);
});
