<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Events\TicketCreatedEvent;
use Illuminate\Support\Facades\Event;
use App\Events\TicketReplyCreatedEvent;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketReplyTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function replies_model_interacts_with_db_table()
    {
        $data = TicketReply::factory()->make();

        TicketReply::create($data->toArray());

        $this->assertDatabaseHas('ticket_replies', $data->only([
            'user_id',
            'ticket_id',
            'content'
        ]));
    }

    /** @test */
    public function replies_model_belongs_to_one_ticket()
    {
        $reply = TicketReply::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $reply->ticket());
        $this->assertInstanceOf(Ticket::class, $reply->ticket);
    }

    /** @test */
    public function replies_model_belongs_to_one_user()
    {
        $reply = TicketReply::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $reply->user());
        $this->assertInstanceOf(User::class, $reply->user);
    }

    /** @test */
    public function repy_model_emits_event_when_reply_is_created()
    {
        Event::fake([
            TicketReplyCreatedEvent::class
        ]);

        $reply = TicketReply::factory()->create();

        Event::assertDispatched(TicketReplyCreatedEvent::class);
    }
}
