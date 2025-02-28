<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use App\Services\RecipientsService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipientsServiceTest extends TestCase
{
    use RefreshDatabase;

    // is istance of Recipientsservice
    // Chain methods
    // owner()
    // departmentAgents()
    // DepartmentAdmins
    // departmentTeam
    // include or exclude current user
    /** @test */
    public function service_can_be_initialized()
    {
        $service = new RecipientsService();

        $this->assertInstanceOf(RecipientsService::class, $service);
    }

    /** @test */
    public function service_collection_contains_ticket_owner()
    {
        $ticket = Ticket::factory()->createQuietly();
        $service = new RecipientsService();

        $recipients = $service->ofTicket($ticket)->owner()->get();

        $this->assertTrue($recipients->contains($ticket->owner));
    }

    /** @test */
    public function service_collection_contains_super_admin_user()
    {
        $super_admin_user = User::factory()->create();
        $ticket = Ticket::factory()->createQuietly(['owner_id' => $super_admin_user->id]);

        $service = new RecipientsService();
        $recipients = $service
            ->ofTicket($ticket)
            ->superAdmins()
            ->owner()
            ->get();

        $this->assertTrue($recipients->contains($super_admin_user));
    }

    /** @test */
    public function service_collection_contains_departmet_admins()
    {
        $department_admin = User::factory()->create();
        $ticket = Ticket::factory()->createQuietly(['owner_id' => $department_admin->id]);

        $service = new RecipientsService();
        $recipients = $service
            ->ofTicket($ticket)
            ->superAdmins()
            ->owner()
            ->departmentAdmins()
            ->get();

        $this->assertTrue($recipients->contains($department_admin));
    }

    /** @test */
    public function service_collection_contains_departmet_agents()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->createQuietly();

        $service = new RecipientsService();
        $recipients = $service
            ->ofTicket($ticket)
            ->superAdmins()
            ->owner()
            ->departmentAdmins()
            ->departmentAgents()
            ->get();

        $this->assertTrue($recipients->contains($user));
    }

    // /** @test */
    // public function service_collection_contains_ticket_agent()
    // {
    //     $user = User::factory()->create();
    //     $ticket = Ticket::factory()->createQuietly(['owner_id' => $user->id]);
    //     $ticket->assignTo($user);

    //     $service = new RecipientsService();
    //     $recipients = $service
    //         ->ofTicket($ticket)
    //         ->superAdmins()
    //         ->owner()
    //         ->departmentAdmins()
    //         ->agent()
    //         ->get();

    //     $this->assertTrue($recipients->contains($user));
    // }

    /** @test */
    // public function service_collection_can_include_current_user()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);
    //     $ticket = Ticket::factory()->createQuietly(['created_by' => $user->id]);
    //     config()->set('support.email.include_current_user', true);

    //     $service = new RecipientsService();
    //     $recipients = $service
    //         ->ofTicket($ticket)
    //         ->superAdmins()
    //         ->owner()
    //         ->departmentAdmins()
    //         ->agent()
    //         ->get();

    //     $this->assertTrue($recipients->contains($user));
    // }

    // /** @test */
    // public function service_collection_can_exclude_current_user()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);
    //     $ticket = Ticket::factory()->createQuietly(['created_by' => $user->id]);
    //     config()->set('support.email.include_current_user', false);

    //     $service = new RecipientsService();
    //     $recipients = $service
    //         ->ofTicket($ticket)
    //         ->superAdmins()
    //         ->owner()
    //         ->departmentAdmins()
    //         ->agent()
    //         ->get();

    //     $this->assertTrue($recipients->doesntContain($user));
    // }
}
