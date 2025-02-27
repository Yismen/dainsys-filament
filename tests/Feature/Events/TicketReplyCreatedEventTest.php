<?php

namespace Tests\Feature\Events;

use Tests\TestCase;
use App\Models\TicketReply;
use App\Mail\TicketReplyCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Events\TicketReplyCreatedEvent;
use App\Listeners\SendTicketReplyCreatedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketReplyCreatedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
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
    }

    /** @test */
    public function when_reply_is_created_an_email_is_sent()
    {
        Mail::fake();

        $reply = TicketReply::factory()->create();

        Mail::assertQueued(TicketReplyCreatedMail::class);
    }
}
