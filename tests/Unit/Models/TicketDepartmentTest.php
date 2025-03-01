<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketDepartmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function departments_model_interacts_with_db_table()
    {
        $data = TicketDepartment::factory()->make();

        TicketDepartment::create($data->toArray());

        $this->assertDatabaseHas('ticket_departments', $data->only([
            'name',
            // 'ticket_prefix',
            'description'
        ]));
    }

    /** @test */
    public function model_uses_soft_delete()
    {
        $this->assertTrue(
            in_array(SoftDeletes::class, class_uses(TicketDepartment::class))
        );

        $ticket_department = TicketDepartment::factory()->create();

        $ticket_department->delete();

        $this->assertSoftDeleted(TicketDepartment::class, [
            'id' => $ticket_department->id
        ]);
    }

    /** @test */
    public function departments_model_has_many_tickets()
    {
        $department = TicketDepartment::factory()->create();

        Ticket::factory()->create(['department_id' => $department->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $department->tickets());
        $this->assertInstanceOf(Ticket::class, $department->tickets->first());
    }

    /** @test */
    public function department_model_set_tickets_completed_attribute()
    {
        $department = TicketDepartment::factory()->create();

        Ticket::factory()->completed()->create(['department_id' => $department->id]);
        Ticket::factory()->create(['department_id' => $department->id]);
        Ticket::factory()->create();

        $this->assertEquals(1, $department->tickets_completed);
    }

    /** @test */
    public function department_model_set_tickets_incompleted_attribute()
    {
        $department = TicketDepartment::factory()->create();
        Ticket::factory()->completed()->create(['department_id' => $department->id]);
        Ticket::factory()->create(['department_id' => $department->id]);

        $this->assertEquals(1, $department->tickets_incompleted);
    }

    /** @test */
    public function department_model_set_completion_rate_attribute()
    {
        $department = TicketDepartment::factory()->create();
        Ticket::factory()->completed()->create(['department_id' => $department->id]);
        Ticket::factory()->create(['department_id' => $department->id, 'completed_at' => now()->addDays(100)]);

        $this->assertEquals(0.5, $department->compliance_rate);
    }

    /** @test */
    public function department_model_set_compliance_rate_attribute()
    {
        $department = TicketDepartment::factory()->create();
        Ticket::factory()->compliant()->create(['department_id' => $department->id]);
        Ticket::factory()->noncompliant()->create(['department_id' => $department->id]);

        $this->assertEquals(0.5, $department->compliance_rate);
    }

    /** @test */
    public function departments_model_parse_prefix_to_all_caps_and_dash_at_the_end()
    {
        $department = TicketDepartment::factory()->create(['name' => 'craziness']);

        $this->assertDatabaseHas(TicketDepartment::class, [
            'ticket_prefix' => 'CRAZ-'
        ]);
    }

    /** @test */
    public function department_model_sets_ticket_prefix_correctly()
    {
        $department = TicketDepartment::factory()->create(['name' => 'administration', 'ticket_prefix' => null]);

        $this->assertDatabaseHas(TicketDepartment::class, [
            'id' => $department->id,
            'ticket_prefix' => 'ADMI-',
        ]);
    }

    /** @test */
    public function department_model_sets_ticket_prefix_correctly_when_name_is_two_words_or_more()
    {
        $department = TicketDepartment::factory()->create(['name' => 'admini service', 'ticket_prefix' => null]);

        $this->assertDatabaseHas(TicketDepartment::class, [
            'id' => $department->id,
            'ticket_prefix' => 'ADSE-',
        ]);
    }

    /** @test */
    public function department_model_sets_ticket_prefix_to_a_new_value_when_prefix_exist_already()
    {
        $department_1 = TicketDepartment::factory()->create(['name' => 'admini']);
        $department_2 = TicketDepartment::factory()->create(['name' => 'administration']);

        $this->assertDatabaseHas(TicketDepartment::class, [
            'id' => $department_1->id,
            'ticket_prefix' => 'ADMI-',
        ]);

        $this->assertDatabaseMissing(TicketDepartment::class, [
            'id' => $department_2->id,
            'ticket_prefix' => 'ADMI-',
        ]);
    }
}
