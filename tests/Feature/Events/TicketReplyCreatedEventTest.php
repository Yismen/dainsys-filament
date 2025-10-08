<?php

use App\Models\TicketReply;
use App\Mail\TicketReplyCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Events\TicketReplyCreatedEvent;
use App\Listeners\SendTicketReplyCreatedMail;

test('event is dispatched', function () {
    Event::fake([
        TicketReplyCreatedEvent::class
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
