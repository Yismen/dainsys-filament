<?php

use App\Events\TicketCompletedEvent;
use App\Listeners\SendTicketCompletedMail;
use App\Mail\TicketCompletedMail;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

test('event is dispatched', function () {
    Event::fake([
        TicketCompletedEvent::class,
    ]);

    // $this->supportSuperAdminUser();
    $ticket = Ticket::factory()->create(['owner_id' => User::factory()->create()]);

    $ticket->complete();

    Event::assertDispatched(TicketCompletedEvent::class);
    Event::assertListening(
        TicketCompletedEvent::class,
        SendTicketCompletedMail::class
    );
});

test('when ticket is created an email is sent', function () {
    Mail::fake();

    $ticket = Ticket::factory()->create(['owner_id' => User::factory()->create()]);
    $ticket->complete();

    Mail::assertQueued(TicketCompletedMail::class);
});
