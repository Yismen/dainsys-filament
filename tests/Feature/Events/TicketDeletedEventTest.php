<?php

use App\Events\TicketCreatedEvent;
use App\Events\TicketDeletedEvent;
use App\Listeners\SendTicketDeletedMail;
use App\Models\Ticket;
use App\Notifications\Tickets\TicketDeletedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

test('event is dispatched', function (): void {
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

test('when ticket is deleted a notification is sent', function (): void {
    Notification::fake();

    $ticket = Ticket::factory()->create();
    $owner = $ticket->owner;
    $ticket->delete();

    Notification::assertSentTo($owner, TicketDeletedNotification::class);
});
