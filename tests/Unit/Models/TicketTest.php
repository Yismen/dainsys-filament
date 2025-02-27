<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Enums\TicketStatuses;
use App\Enums\TicketPriorities;
use App\Models\TicketDepartment;
use App\Events\TicketCreatedEvent;
use App\Events\TicketAssignedEvent;
use App\Events\TicketReopenedEvent;
use App\Events\TicketCompletedEvent;
use App\Traits\EnsureDateNotWeekend;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketTest extends TestCase
{
    use RefreshDatabase;
    use EnsureDateNotWeekend;
    // use EnsureDateNotWeekend;

    /** @test */
    public function tickets_model_interacts_with_db_table()
    {
        $data = Ticket::factory()->make();

        Ticket::create($data->toArray());

        $this->assertDatabaseHas('tickets', $data->only([
            'owner_id',
            'department_id',
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
    }

    /** @test */
    public function tickets_model_belongs_to_owner()
    {
        $ticket = Ticket::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $ticket->owner());
        $this->assertInstanceOf(User::class, $ticket->owner);
    }

    /** @test */
    public function tickets_model_belongs_to_agent()
    {
        $ticket = Ticket::factory()->assigned()->create();


        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $ticket->agent());
        $this->assertInstanceOf(User::class, $ticket->agent);
    }

    /** @test */
    public function tickets_model_belongs_to_one_department()
    {
        $ticket = Ticket::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $ticket->department());
        $this->assertInstanceOf(TicketDepartment::class, $ticket->department);
    }

    /** @test */
    public function tickets_model_has_many_replies()
    {
        $ticket = Ticket::factory()->create();

        TicketReply::factory()->create(['ticket_id' => $ticket->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $ticket->replies());
        $this->assertInstanceOf(TicketReply::class, $ticket->replies->first());
    }

    /** @test */
    public function tickets_model_updates_expected_at_when_priority_is_normal()
    {
        $date = now();
        $ticket = Ticket::factory()->create(['created_at' => $date->copy()]);

        $ticket->update(['priority' => TicketPriorities::Normal]);
        $ticket->touch();

        $this->assertDatabaseHas(Ticket::class, [
            'expected_at' => $this->ensureNotWeekend($date->copy()->addDays(+2)),
        ]);
    }

    /** @test */
    public function tickets_model_updates_expected_at_when_priority_is_medium()
    {
        $date = now();
        $ticket = Ticket::factory()->create(['created_at' => $date->copy()]);

        $ticket->update(['priority' => TicketPriorities::Medium]);
        $ticket->touch();

        $this->assertDatabaseHas(Ticket::class, [
            'expected_at' => $this->ensureNotWeekend($date->copy()->addDay()),
        ]);
    }

    /** @test */
    public function tickets_model_updates_expected_at_when_priority_is_high()
    {
        $date = now();
        $ticket = Ticket::factory()->create(['created_at' => $date->copy()]);

        $ticket->update(['priority' => TicketPriorities::High]);
        $ticket->touch();

        $this->assertDatabaseHas(Ticket::class, [
            'expected_at' => $this->ensureNotWeekend($date->copy()->addMinutes(4 * 60)),
        ]);
    }

    /** @test */
    public function tickets_model_updates_expected_at_when_priority_is_emergency()
    {
        $date = now();
        $ticket = Ticket::factory()->create(['created_at' => $date->copy()]);

        $ticket->update(['priority' => TicketPriorities::Emergency]);
        $ticket->touch();

        $this->assertDatabaseHas(Ticket::class, [
            'expected_at' => $this->ensureNotWeekend($date->copy()->addMinutes(30)),
        ]);
    }

    /** @test */
    public function ticket_model_updates_reference_correcly()
    {
        $department_1 = TicketDepartment::factory()->create();
        $department_2 = TicketDepartment::factory()->create();
        $ticket_1 = Ticket::factory()->create(['department_id' => $department_1->id]);
        $ticket_2 = Ticket::factory()->create(['department_id' => $department_2->id]);
        $ticket_3 = Ticket::factory()->create(['department_id' => $department_1->id]);

        $this->assertDatabaseHas(Ticket::class, [
            'id' => $ticket_1->id,
            'reference' => $department_1->ticket_prefix . '000001',
        ]);

        $this->assertDatabaseHas(Ticket::class, [
            'id' => $ticket_2->id,
            'reference' => $department_2->ticket_prefix . '000001',
        ]);

        $this->assertDatabaseHas(Ticket::class, [
            'id' => $ticket_3->id,
            'reference' => $department_1->ticket_prefix . '000002',
        ]);
    }

    /** @test */
    public function tickets_model_can_assign_an_agent()
    {
        $department = TicketDepartment::factory()->create();
        $ticket = Ticket::factory()->unassigned()->create(['department_id' => $department->id]);
        $agent = User::factory()->create();

        $ticket->assignTo($agent);

        $this->assertDatabaseHas(Ticket::class, [
            'assigned_to' => $agent->id,
            'assigned_at' => $ticket->assigned_at,
            'status' => TicketStatuses::InProgress,
        ]);
    }

    /** @test */
    // public function tickets_model_can_not_assign_tickets_to_agents_from_other_departments()
    // {
    //     $this->expectException(DifferentDepartmentException::class);

    //     $department = Department::factory()->create();
    //     $agent = DepartmentRole::factory()->agent()->create(['department_id' => $department->id]);
    //     $ticket = Ticket::factory()->unassigned()->create(['department_id' => DepartmentFactory::new()->create()]);

    //     $ticket->assignTo($agent);
    // }

    /** @test */
    public function tickets_model_can_be_completed()
    {
        $agent = User::factory()->create();
        $department = TicketDepartment::factory()->create();
        $ticket = Ticket::factory()->assigned()->create(['department_id' => $department->id]);

        $ticket->complete();

        $this->assertDatabaseHas(Ticket::class, [
            'completed_at' => $ticket->completed_at,
        ]);
    }

    /** @test */
    public function tickets_model_update_status_to_pending_when_ticket_is_created()
    {
        $ticket = Ticket::factory()->create(['status' => TicketStatuses::InProgress]);

        $this->assertDatabaseHas(Ticket::class, [
            'status' => TicketStatuses::Pending,
        ]);
    }

    /** @test */
    public function tickets_model_update_status_to_expired_when_expected_at_has_passed()
    {
        $date = now();
        $ticket = Ticket::factory()->create(['status' => TicketStatuses::InProgress]);

        $this->travelTo($date->copy()->addDays(20));
        $ticket->touch();

        $this->assertDatabaseHas(Ticket::class, [
            'status' => TicketStatuses::PendingExpired,
        ]);
    }

    /** @test */
    public function tickets_model_update_status_to_in_progress()
    {
        $ticket = Ticket::factory()->create();
        $ticket->assignTo(User::factory()->create());

        $this->assertDatabaseHas(Ticket::class, [
            'status' => TicketStatuses::InProgress,
        ]);
    }

    /** @test */
    public function tickets_model_update_status_to_in_status_expired()
    {
        $date = now();
        $ticket = Ticket::factory()->assigned()->create();

        $this->travelTo($date->copy()->addDays(40));
        $ticket->touch();

        $this->assertDatabaseHas(Ticket::class, [
            'status' => TicketStatuses::InProgressExpired,
        ]);
    }

    /** @test */
    public function tickets_model_update_status_to_in_completed_compliant()
    {
        $ticket = Ticket::factory()->assigned()->create();

        $ticket->complete();

        $this->assertDatabaseHas(Ticket::class, [
            'status' => TicketStatuses::Completed,
        ]);
    }

    /** @test */
    public function tickets_model_update_status_to_in_completed_expired()
    {
        $date = now();
        $ticket = Ticket::factory()->assigned()->create();

        $this->travelTo($date->copy()->addDays(40));
        $ticket->complete();

        $this->assertDatabaseHas(Ticket::class, [
            'status' => TicketStatuses::CompletedExpired,
        ]);
    }

    /** @test */
    public function ticket_model_emit_event_when_ticket_is_created()
    {
        Event::fake(TicketCreatedEvent::class);
        $ticket = Ticket::factory()->create();

        Event::assertDispatched(TicketCreatedEvent::class);
    }

    /** @test */
    public function ticket_model_emit_event_when_ticket_is_completed()
    {
        Event::fake();
        $ticket = Ticket::factory()->create();

        $ticket->complete();

        Event::assertDispatched(TicketCompletedEvent::class);
    }

    /** @test */
    public function ticket_model_emit_event_when_ticket_is_assigned()
    {
        Event::fake(TicketAssignedEvent::class);
        $ticket = Ticket::factory()->create();

        $ticket->assignTo(User::factory()->create());

        Event::assertDispatched(TicketAssignedEvent::class);
    }
    /** @test */
    public function ticket_model_emit_event_when_ticket_is_reopened()
    {
        Event::fake(TicketReopenedEvent::class);
        $ticket = Ticket::factory()->create();

        $ticket->reOpen();

        Event::assertDispatched(TicketReopenedEvent::class);
    }

    /** @test */
    public function ticket_model_get_completed_attribute()
    {
        Ticket::factory()->completed()->create();
        Ticket::factory()->create();

        $this->assertEquals(1, Ticket::completed()->count());
    }

    /** @test */
    public function ticket_model_get_incompleted_attribute()
    {
        Ticket::factory()->incompleted()->create();
        Ticket::factory()->create();

        $this->assertEquals(2, Ticket::incompleted()->count());
    }

    /** @test */
    public function ticket_model_get_is_assigned_to_agent_method()
    {
        $agent = User::factory()->create();
        $ticket = Ticket::factory()->create();

        $ticket->assignTo($agent);

        $this->assertTrue($ticket->isAssignedTo($agent));
    }

    /** @test */
    public function ticket_model_get_compliant_attribute()
    {
        Ticket::factory()->compliant()->create();
        Ticket::factory()->create();

        $this->assertEquals(1, Ticket::compliant()->count());
    }

    /** @test */
    public function ticket_model_get_noncompliant_attribute()
    {
        Ticket::factory()->noncompliant()->create();
        Ticket::factory()->create();

        $this->assertEquals(1, Ticket::nonCompliant()->count());
    }
}
