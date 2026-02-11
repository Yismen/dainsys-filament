<?php

use App\Events\TicketCompletedEvent;
use App\Events\TicketCreatedEvent;
use App\Events\TicketReopenedEvent;
use App\Listeners\SendTicketReopenedMail;
use App\Mail\TicketReopenedMail;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

test('event is dispatched', function (): void {
    Event::fake([
        TicketReopenedEvent::class,
        TicketCreatedEvent::class,
        TicketCompletedEvent::class,
    ]);

    // $this->supportSuperAdminUser();
    $ticket = Ticket::factory()->create();

    $this->actingAs(User::factory()->create());
    $ticket->reOpen('Reopen for testing');

    Event::assertDispatched(TicketReopenedEvent::class);
    Event::assertListening(
        TicketReopenedEvent::class,
        SendTicketReopenedMail::class
    );
});

test('when ticket is created an email is sent', function (): void {
    Mail::fake();

    $this->actingAs(User::factory()->create());
    $ticket = Ticket::factory()->assigned()->create();
    $ticket->close('Testing');
    $ticket->reOpen('Ticket');

    Mail::assertQueued(TicketReopenedMail::class);
});
