<?php

use App\Events\TicketReplyCreatedEvent;
use App\Listeners\SendTicketReplyCreatedMail;
use App\Mail\TicketReplyCreatedMail;
use App\Models\TicketReply;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

test('event is dispatched', function () {
    Event::fake([
        TicketReplyCreatedEvent::class,
    ]);

    // $this->supportSuperAdminUser();
    $reply = TicketReply::factory()->create();

    Event::assertDispatched(TicketReplyCreatedEvent::class);
    Event::assertListening(
        TicketReplyCreatedEvent::class,
        SendTicketReplyCreatedMail::class
    );
});

test('when reply is created an email is sent', function () {
    Mail::fake();

    $reply = TicketReply::factory()->create();

    Mail::assertQueued(TicketReplyCreatedMail::class);
});
