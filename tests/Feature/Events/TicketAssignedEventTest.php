<?php

use App\Models\User;
use App\Models\Ticket;
use App\Mail\TicketAssignedMail;
use App\Events\TicketAssignedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendTicketAssignedMail;

test('event is dispatched', function () {
    Event::fake([
        TicketAssignedEvent::class
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
