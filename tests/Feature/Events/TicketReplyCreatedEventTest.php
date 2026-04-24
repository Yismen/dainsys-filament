<?php

use App\Events\TicketCreatedEvent;
use App\Events\TicketReplyCreatedEvent;
use App\Listeners\SendTicketReplyCreatedMail;
use App\Models\TicketReply;
use App\Notifications\Tickets\TicketReplyCreatedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

test('event is dispatched', function (): void {
    Event::fake([
        TicketReplyCreatedEvent::class,
        TicketCreatedEvent::class,
    ]);

    // $this->supportSuperAdminUser();
    $reply = TicketReply::factory()->create();

    Event::assertDispatched(TicketReplyCreatedEvent::class);
    Event::assertListening(
        TicketReplyCreatedEvent::class,
        SendTicketReplyCreatedMail::class
    );
});

test('when reply is created a notification is sent', function (): void {
    Notification::fake();

    $reply = TicketReply::factory()->create();
    $owner = $reply->ticket->owner;

    Notification::assertSentTo($owner, TicketReplyCreatedNotification::class);
});
