<?php

use App\Console\Commands\UpdateTicketStatus;
use App\Enums\TicketStatuses;
use App\Events\TicketCompletedEvent;
use App\Events\TicketCreatedEvent;
use App\Models\Ticket;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    Event::fake([
        TicketCreatedEvent::class,
        TicketCompletedEvent::class,
    ]);
});

test('command is schedulled for evey thirty minutes', function (): void {
    $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->filter(function ($element) {
            return str($element->command)->contains('dainsys:update-ticket-status');
        })->first();

    expect($addedToScheduler)->not->toBeNull();
    expect($addedToScheduler->expression)->toEqual('*/30 * * * *');
});

test('update tickets status', function (): void {
    $ticket = Ticket::factory()->create();

    $this->travelTo(now()->addDays(50));
    $this->artisan(UpdateTicketStatus::class);

    $this->assertDatabaseHas(Ticket::class, [
        'status' => TicketStatuses::PendingExpired,
    ]);
});

test('update tickets only updates ticket 2 tickets', function (): void {
    $ticket_1 = Ticket::factory()->completed()->create();
    $ticket_2 = Ticket::factory()->create();

    $this->travelTo(now()->addDays(50));
    $this->artisan(UpdateTicketStatus::class);

    $this->assertDatabaseHas(Ticket::class, [
        'id' => $ticket_1->id,
        'status' => TicketStatuses::Completed,
    ]);

    $this->assertDatabaseHas(Ticket::class, [
        'id' => $ticket_2->id,
        'status' => TicketStatuses::PendingExpired,
    ]);
});
