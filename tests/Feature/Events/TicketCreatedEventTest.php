<?php

use App\Events\TicketCreatedEvent;
use App\Listeners\SendTicketCreatedMail;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Tickets\TicketCreatedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

test('event is dispatched', function (): void {
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

test('when ticket is created a notification is sent', function (): void {
    Notification::fake();

    $owner = User::factory()->create();
    $ticket = Ticket::factory()->create(['owner_id' => $owner]);
    $this->actingAs(User::factory()->create());
    $ticket->close('Comment');

    Notification::assertSentTo($owner, TicketCreatedNotification::class);
});
