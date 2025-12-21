<?php

use App\Events\TicketAssignedEvent;
use App\Listeners\SendTicketAssignedMail;
use App\Mail\TicketAssignedMail;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

test('event is dispatched', function () {
    Event::fake([
        TicketAssignedEvent::class,
    ]);

    $ticket = Ticket::factory()->create();

    $ticket->assignTo(User::factory()->create());

    Event::assertDispatched(TicketAssignedEvent::class);
    Event::assertListening(
        TicketAssignedEvent::class,
        SendTicketAssignedMail::class
    );
});

test('when ticket is created an email is sent', function () {
    Mail::fake();

    $ticket = Ticket::factory()->create();
    $ticket->assignTo(User::factory()->create());

    Mail::assertQueued(TicketAssignedMail::class);
});
