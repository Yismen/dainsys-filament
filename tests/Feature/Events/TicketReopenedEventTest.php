<?php

use App\Events\TicketCompletedEvent;
use App\Events\TicketCreatedEvent;
use App\Events\TicketReopenedEvent;
use App\Listeners\SendTicketReopenedMail;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Tickets\TicketReopenedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

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

test('when ticket is reopened a notification is sent', function (): void {
    Notification::fake();

    $owner = User::factory()->create();
    $ticket = Ticket::factory()->assigned()->create(['owner_id' => $owner->id]);
    $this->actingAs(User::factory()->create());
    $owner = $ticket->owner;
    $ticket->close('Testing');
    $ticket->reOpen('Ticket');

    Notification::assertSentTo($owner, TicketReopenedNotification::class);
});
