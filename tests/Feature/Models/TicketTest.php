<?php

use App\Enums\TicketPriorities;
use App\Enums\TicketStatuses;
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

uses(\App\Traits\EnsureDateNotWeekend::class);

beforeEach(function () {
    Event::fake([
        TicketCreatedEvent::class,
        TicketAssignedEvent::class,
        TicketCompletedEvent::class,
        TicketDeletedEvent::class,
        TicketReopenedEvent::class,
        TicketReplyCreatedEvent::class,

    ]);
});

test('tickets model interacts with db table', function () {
    $data = Ticket::factory()->make();

    Ticket::create($data->toArray());

    $this->assertDatabaseHas('tickets', $data->only([
        'owner_id',
        'subject',
        'description',
        // 'assigned_to',
        // 'assigned_at',
        // 'expected_at',
        // 'reference',
        // 'images',
        // 'completed_at',
        'status',
        'priority',
    ]));
});

test('tickets model belongs to owner', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->owner())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($ticket->owner)->toBeInstanceOf(User::class);
});

test('tickets model belongs to agent', function () {
    $ticket = Ticket::factory()->assigned()->create();

    expect($ticket->agent())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($ticket->agent)->toBeInstanceOf(User::class);
});

test('tickets model has many replies', function () {
    $ticket = Ticket::factory()->create();

    TicketReply::factory()->create(['ticket_id' => $ticket->id]);

    expect($ticket->replies())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($ticket->replies->first())->toBeInstanceOf(TicketReply::class);
});

test('tickets model updates expected at when priority is normal', function () {
    $date = now();
    $ticket = Ticket::factory()->create(['created_at' => $date->copy()]);

    $ticket->update(['priority' => TicketPriorities::Normal]);
    $ticket->touch();

    $this->assertDatabaseHas(Ticket::class, [
        'expected_at' => $this->ensureNotWeekend($date->copy()->addDays(+2)),
    ]);
});

test('tickets model updates expected at when priority is medium', function () {
    $date = now();
    $ticket = Ticket::factory()->create(['created_at' => $date->copy()]);

    $ticket->update(['priority' => TicketPriorities::Medium]);
    $ticket->touch();

    $this->assertDatabaseHas(Ticket::class, [
        'expected_at' => $this->ensureNotWeekend($date->copy()->addDay()),
    ]);
});

test('tickets model updates expected at when priority is high', function () {
    $date = now();
    $ticket = Ticket::factory()->create(['created_at' => $date->copy()]);

    $ticket->update(['priority' => TicketPriorities::High]);
    $ticket->touch();

    $this->assertDatabaseHas(Ticket::class, [
        'expected_at' => $this->ensureNotWeekend($date->copy()->addMinutes(4 * 60)),
    ]);
});

test('tickets model updates expected at when priority is emergency', function () {
    $date = now();
    $ticket = Ticket::factory()->create(['created_at' => $date->copy()]);

    $ticket->update(['priority' => TicketPriorities::Emergency]);
    $ticket->touch();

    $this->assertDatabaseHas(Ticket::class, [
        'expected_at' => $this->ensureNotWeekend($date->copy()->addMinutes(30)),
    ]);
});

test('ticket model updates reference correcly', function () {
    $ticket_1 = Ticket::factory()->create();
    $ticket_2 = Ticket::factory()->create();
    $ticket_3 = Ticket::factory()->create();

    $this->assertDatabaseHas(Ticket::class, [
        'id' => $ticket_1->id,
        'reference' => 'ECC-IT-000001',
    ]);

    $this->assertDatabaseHas(Ticket::class, [
        'id' => $ticket_2->id,
        'reference' => 'ECC-IT-000002',
    ]);

    $this->assertDatabaseHas(Ticket::class, [
        'id' => $ticket_3->id,
        'reference' => 'ECC-IT-000003',
    ]);
});

test('tickets model can assign an agent', function () {
    $ticket = Ticket::factory()->unassigned()->create();
    $agent = User::factory()->create();

    $ticket->assignTo($agent);

    $this->assertDatabaseHas(Ticket::class, [
        'assigned_to' => $agent->id,
        'assigned_at' => $ticket->assigned_at,
        'status' => TicketStatuses::InProgress,
    ]);
});

test('tickets model can be completed', function () {
    $agent = User::factory()->create();
    $ticket = Ticket::factory()->assigned()->create();

    $ticket->complete();

    $this->assertDatabaseHas(Ticket::class, [
        'completed_at' => $ticket->completed_at,
    ]);
});

test('tickets model update status to pending when ticket is created', function () {
    $ticket = Ticket::factory()->create(['status' => TicketStatuses::InProgress]);

    $this->assertDatabaseHas(Ticket::class, [
        'status' => TicketStatuses::Pending,
    ]);
});

test('tickets model update status to expired when expected at has passed', function () {
    $date = now();
    $ticket = Ticket::factory()->create(['status' => TicketStatuses::InProgress]);

    $this->travelTo($date->copy()->addDays(20));
    $ticket->touch();

    $this->assertDatabaseHas(Ticket::class, [
        'status' => TicketStatuses::PendingExpired,
    ]);
});

test('tickets model update status to in progress', function () {
    $ticket = Ticket::factory()->create();
    $ticket->assignTo(User::factory()->create());

    $this->assertDatabaseHas(Ticket::class, [
        'status' => TicketStatuses::InProgress,
    ]);
});

test('tickets model update status to in status expired', function () {
    $date = now();
    $ticket = Ticket::factory()->assigned()->create();

    $this->travelTo($date->copy()->addDays(40));
    $ticket->touch();

    $this->assertDatabaseHas(Ticket::class, [
        'status' => TicketStatuses::InProgressExpired,
    ]);
});

test('tickets model update status to in completed compliant', function () {
    $ticket = Ticket::factory()->assigned()->create();

    $ticket->complete();

    $this->assertDatabaseHas(Ticket::class, [
        'status' => TicketStatuses::Completed,
    ]);
});

test('tickets model update status to in completed expired', function () {
    $date = now();
    $ticket = Ticket::factory()->assigned()->create();

    $this->travelTo($date->copy()->addDays(40));
    $ticket->complete();

    $this->assertDatabaseHas(Ticket::class, [
        'status' => TicketStatuses::CompletedExpired,
    ]);
});

test('ticket model emit event when ticket is created', function () {
    Event::fake(TicketCreatedEvent::class);
    $ticket = Ticket::factory()->create();

    Event::assertDispatched(TicketCreatedEvent::class);
});

test('ticket model emit event when ticket is completed', function () {
    Event::fake();
    $ticket = Ticket::factory()->create();

    $ticket->complete();

    Event::assertDispatched(TicketCompletedEvent::class);
});

test('ticket model emit event when ticket is assigned', function () {
    Event::fake(TicketAssignedEvent::class);
    $ticket = Ticket::factory()->create();

    $ticket->assignTo(User::factory()->create());

    Event::assertDispatched(TicketAssignedEvent::class);
});

test('ticket model emit event when ticket is reopened', function () {
    Event::fake(TicketReopenedEvent::class);
    $ticket = Ticket::factory()->create();

    $ticket->reOpen();

    Event::assertDispatched(TicketReopenedEvent::class);
});

test('ticket model get completed attribute', function () {
    Ticket::factory()->completed()->create();
    Ticket::factory()->create();

    expect(Ticket::completed()->count())->toEqual(1);
});

test('ticket model get incompleted attribute', function () {
    Ticket::factory()->incompleted()->create();
    Ticket::factory()->create();

    expect(Ticket::incompleted()->count())->toEqual(2);
});

test('ticket model get is assigned to agent method', function () {
    $agent = User::factory()->create();
    $ticket = Ticket::factory()->create();

    $ticket->assignTo($agent);

    expect($ticket->isAssignedTo($agent))->toBeTrue();
});

test('ticket model get compliant attribute', function () {
    Ticket::factory()->compliant()->create();
    Ticket::factory()->create();

    expect(Ticket::compliant()->count())->toEqual(1);
});

test('ticket model get noncompliant attribute', function () {
    Ticket::factory()->noncompliant()->create();
    Ticket::factory()->create();

    expect(Ticket::nonCompliant()->count())->toEqual(1);
});
