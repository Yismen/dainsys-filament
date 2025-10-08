<?php

use App\Models\User;
use App\Models\Ticket;
use App\Mail\TicketCompletedMail;
use App\Events\TicketCompletedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendTicketCompletedMail;

test('event is dispatched', function () {
    Event::fake([
        TicketCompletedEvent::class
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
