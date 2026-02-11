<?php

use App\Events\TicketAssignedEvent;
use App\Events\TicketCompletedEvent;
use App\Events\TicketCreatedEvent;
use App\Events\TicketDeletedEvent;
use App\Events\TicketReopenedEvent;
use App\Events\TicketReplyCreatedEvent;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    Event::fake([
        TicketAssignedEvent::class,
        TicketCreatedEvent::class,
        TicketCompletedEvent::class,
        TicketDeletedEvent::class,
        TicketReopenedEvent::class,
        TicketReplyCreatedEvent::class,

    ]);
});

test('replies model interacts with db table', function (): void {
    $data = TicketReply::factory()->make();

    TicketReply::create($data->toArray());

    $this->assertDatabaseHas('ticket_replies', $data->only([
        'user_id',
        'ticket_id',
        'content',
    ]));
});

test('replies model belongs to one ticket', function (): void {
    $reply = TicketReply::factory()->create();

    expect($reply->ticket())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($reply->ticket)->toBeInstanceOf(Ticket::class);
});

test('replies model belongs to one user', function (): void {
    $reply = TicketReply::factory()->create();

    expect($reply->user())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($reply->user)->toBeInstanceOf(User::class);
});

test('repy model emits event when reply is created', function (): void {
    Event::fake([
        TicketReplyCreatedEvent::class,
    ]);

    $reply = TicketReply::factory()->create();

    Event::assertDispatched(TicketReplyCreatedEvent::class);
});
