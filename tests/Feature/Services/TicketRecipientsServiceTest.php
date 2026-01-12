<?php

use App\Enums\TicketRoles;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketRecipientsService;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
});

test('service can be initialized', function () {
    $service = new TicketRecipientsService;

    expect($service)->toBeInstanceOf(TicketRecipientsService::class);
});

test('service collection contains ticket owner', function () {
    $ticket = Ticket::factory()->createQuietly();
    $service = new TicketRecipientsService;

    $recipients = $service->ofTicket($ticket)->owner()->get();

    expect($recipients->contains($ticket->owner))->toBeTrue();
});

test('service collection contains super admin user', function () {
    $regular_user = User::factory()->create();
    $super_admin_user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'super_admin']);
    $super_admin_user->assignRole($role);

    $ticket = Ticket::factory()->create();

    $recipients =(new TicketRecipientsService)
        ->ofTicket($ticket)
        ->superAdmins()
        ->get();

    expect($recipients->contains($regular_user))->toBeFalse();

    expect($recipients->contains($super_admin_user))->toBeTrue();
});

test('service collection contains ticket admins', function () {
    $regular_user = User::factory()->create();
    $tickets_admin = User::factory()->create();
    $role = Role::firstOrCreate(['name' => TicketRoles::Admin->value]);
    $tickets_admin->assignRole($role);

    $ticket = Ticket::factory()->create();

    $recipients =(new TicketRecipientsService)
        ->ofTicket($ticket)
        ->ticketAdmins()
        ->get();

    expect($recipients->contains($regular_user))->toBeFalse();

    expect($recipients->contains($tickets_admin))->toBeTrue();
});

test('service collection contains ticket operators', function () {
    $regular_user = User::factory()->create();
    $ticket_operators = User::factory()->create();
    $role = Role::firstOrCreate(['name' => TicketRoles::Operator->value]);
    $ticket_operators->assignRole($role);

    $ticket = Ticket::factory()->create();

    $recipients =(new TicketRecipientsService)
        ->ofTicket($ticket)
        ->ticketOperators()
        ->get();

    expect($recipients->contains($regular_user))->toBeFalse();

    expect($recipients->contains($ticket_operators))->toBeTrue();
});
