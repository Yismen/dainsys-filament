<?php

use App\Events\TicketAssignedEvent;
use App\Events\TicketCreatedEvent;
use App\Listeners\SendTicketAssignedMail;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Tickets\TicketAssignedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

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

test('when ticket is assigned a notification is sent', function (): void {
    Notification::fake();

    $ticket = Ticket::factory()->create();
    $assignee = User::factory()->create();
    $ticket->assignTo($assignee);

    Notification::assertSentTo($assignee, TicketAssignedNotification::class);
});
