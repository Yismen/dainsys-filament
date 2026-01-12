<?php

use App\Enums\TicketRoles;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\Traits\InteractWithSupportTickets;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

it('ensure user model uses trait', function () {
    expect(User::class)
        ->toUseTrait(InteractWithSupportTickets::class);
});

it('has many tickets', function () {
    $user = User::factory()->create();

    Ticket::factory()->create(['owner_id' => $user->id]);

    expect($user->tickets())->toBeInstanceOf(HasMany::class);
    expect($user->tickets->first())->toBeInstanceOf(Ticket::class);
});

it('has many assignedTickets', function () {
    $user = User::factory()->create();

    Ticket::factory()->create(['assigned_to' => $user->id]);

    expect($user->assignedTickets())->toBeInstanceOf(HasMany::class);
    expect($user->assignedTickets->first())->toBeInstanceOf(Ticket::class);
});

it('has is tickets admin function', function () {
    $user = User::factory()->create();

    expect($user->isTicketsAdmin())->toBeFalse();

    $role = Role::firstOrCreate(['name' => TicketRoles::Admin->value]);

    $user->assignRole($role);

    expect($user->isTicketsAdmin())->toBeTrue();
});

it('has is tickets operator function', function () {
    $user = User::factory()->create();

    expect($user->isTicketsOperator())->toBeFalse();

    $role = Role::firstOrCreate(['name' => TicketRoles::Operator->value]);

    $user->assignRole($role);

    expect($user->isTicketsOperator())->toBeTrue();
});
