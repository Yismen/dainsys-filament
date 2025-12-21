<?php

use App\Events\TicketReopenedEvent;
use App\Listeners\SendTicketReopenedMail;
use App\Mail\TicketReopenedMail;
use App\Models\Ticket;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

test('event is dispatched', function () {
    Event::fake([
        TicketReopenedEvent::class,
    ]);

    // $this->supportSuperAdminUser();
    $ticket = Ticket::factory()->create();

    $ticket->reopen();

    Event::assertDispatched(TicketReopenedEvent::class);
    Event::assertListening(
        TicketReopenedEvent::class,
        SendTicketReopenedMail::class
    );
});

test('when ticket is created an email is sent', function () {
    Mail::fake();

    // $this->supportSuperAdminUser();
    $ticket = Ticket::factory()->assigned()->create();
    $ticket->reopen();

    Mail::assertQueued(TicketReopenedMail::class);
});
