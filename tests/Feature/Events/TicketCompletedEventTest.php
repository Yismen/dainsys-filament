<?php

use App\Events\TicketCompletedEvent;
use App\Events\TicketCreatedEvent;
use App\Listeners\SendTicketCompletedMail;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Tickets\TicketCompletedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

test('event is dispatched when the complete method is called', function (): void {
    Event::fake([
        TicketCompletedEvent::class,
        TicketCreatedEvent::class,
    ]);

    // $this->supportSuperAdminUser();
    $ticket = Ticket::factory()->create(['owner_id' => User::factory()->create()]);

    $this->actingAs(User::factory()->create());
    $ticket->complete('Comment');

    Event::assertDispatched(TicketCompletedEvent::class);
    Event::assertListening(
        TicketCompletedEvent::class,
        SendTicketCompletedMail::class
    );
});

test('event is dispatched when the close method is called', function (): void {
    Event::fake([
        TicketCompletedEvent::class,
        TicketCreatedEvent::class,
    ]);

    // $this->supportSuperAdminUser();
    $ticket = Ticket::factory()->create(['owner_id' => User::factory()->create()]);

    $this->actingAs(User::factory()->create());
    $ticket->close('Comment');

    Event::assertDispatched(TicketCompletedEvent::class);
    Event::assertListening(
        TicketCompletedEvent::class,
        SendTicketCompletedMail::class
    );
});

test('when ticket is completed a notification is sent', function (): void {
    Notification::fake();

    $owner = User::factory()->create();
    $ticket = Ticket::factory()->create(['owner_id' => $owner]);
    $this->actingAs(User::factory()->create());
    $ticket->close('Comment');

    Notification::assertSentTo($owner, TicketCompletedNotification::class);
});
