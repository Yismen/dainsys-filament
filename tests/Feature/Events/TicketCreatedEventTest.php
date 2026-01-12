<?php

use App\Events\TicketCreatedEvent;
use App\Listeners\SendTicketCreatedMail;
use App\Mail\TicketCreatedMail;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

test('event is dispatched', function () {
    Event::fake([
        TicketCreatedEvent::class,
    ]);

    $ticket = Ticket::factory()->create();

    Event::assertDispatched(TicketCreatedEvent::class);
    Event::assertListening(
        TicketCreatedEvent::class,
        SendTicketCreatedMail::class
    );
});

test('when ticket is created an email is sent', function () {
    Mail::fake();

    $ticket = Ticket::factory()->create(['owner_id' => User::factory()->create()]);
    $this->actingAs(User::factory()->create());
    $ticket->close('Comment');

    Mail::assertQueued(TicketCreatedMail::class);
});
