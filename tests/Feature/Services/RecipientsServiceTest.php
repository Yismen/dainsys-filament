<?php

use App\Models\Ticket;
use App\Models\User;
use App\Services\RecipientsService;

test('service can be initialized', function () {
    $service = new RecipientsService;

    expect($service)->toBeInstanceOf(RecipientsService::class);
});

test('service collection contains ticket owner', function () {
    $ticket = Ticket::factory()->createQuietly();
    $service = new RecipientsService;

    $recipients = $service->ofTicket($ticket)->owner()->get();

    expect($recipients->contains($ticket->owner))->toBeTrue();
});

test('service collection contains super admin user', function () {
    $super_admin_user = User::factory()->create();
    $ticket = Ticket::factory()->createQuietly(['owner_id' => $super_admin_user->id]);

    $service = new RecipientsService;
    $recipients = $service
        ->ofTicket($ticket)
        ->superAdmins()
        ->owner()
        ->get();

    expect($recipients->contains($super_admin_user))->toBeTrue();
});

test('service collection contains departmet admins', function () {
    $department_admin = User::factory()->create();
    $ticket = Ticket::factory()->createQuietly(['owner_id' => $department_admin->id]);

    $service = new RecipientsService;
    $recipients = $service
        ->ofTicket($ticket)
        ->superAdmins()
        ->owner()
        ->departmentAdmins()
        ->get();

    expect($recipients->contains($department_admin))->toBeTrue();
});

test('service collection contains departmet agents', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->createQuietly();

    $service = new RecipientsService;
    $recipients = $service
        ->ofTicket($ticket)
        ->superAdmins()
        ->owner()
        ->departmentAdmins()
        ->departmentAgents()
        ->get();

    expect($recipients->contains($user))->toBeTrue();
});

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
