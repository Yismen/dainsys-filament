<?php

namespace Tests\Feature\Console\Commands;

use Tests\TestCase;
use App\Models\Ticket;
use App\Enums\TicketStatuses;
use App\Console\Commands\UpdateTicketStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTicketStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function testExample()
    {
        $this->assertTrue(true);
    }

    // /** @test */
    // public function command_is_schedulled_for_evey_thirty_minutes()
    // {
    //     $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
    //         ->filter(function ($element) {
    //             return str($element->command)->contains('support:update-ticket-status');
    //         })->first();

    //     $this->assertNotNull($addedToScheduler);
    //     $this->assertEquals('0,30 * * * *', $addedToScheduler->expression);
    // }

    // /** @test */
    // public function update_tickets_status()
    // {
    //     $ticket = Ticket::factory()->create();

    //     $this->travelTo(now()->addDays(50));
    //     $this->artisan(UpdateTicketStatus::class);

    //     $this->assertDatabaseHas(Ticket::class, [
    //         'status' => TicketStatuses::PendingExpired
    //     ]);
    // }

    /** @test */
    // public function update_tickets_only_updates_ticket_2_tickets()
    // {
    //     $ticket_1 = Ticket::factory()->completed()->create();
    //     $ticket_2 = Ticket::factory()->create();

    //     $this->travelTo(now()->addDays(50));
    //     $this->artisan(UpdateTicketStatus::class);

    //     $this->assertDatabaseHas(Ticket::class, [
    //         'id' => $ticket_1->id,
    //         'status' => TicketStatuses::Completed
    //     ]);

    //     $this->assertDatabaseHas(Ticket::class, [
    //         'id' => $ticket_2->id,
    //         'status' => TicketStatuses::PendingExpired
    //     ]);
    // }
}
