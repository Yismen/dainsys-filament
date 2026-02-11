<?php

use App\Enums\SupportRoles;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\Traits\InteractWithSupportTickets;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

it('ensure user model uses trait', function (): void {
    expect(User::class)
        ->toUseTrait(InteractWithSupportTickets::class);
});

it('has many tickets', function (): void {
    $user = User::factory()->create();

    Ticket::factory()->create(['owner_id' => $user->id]);

    expect($user->tickets())->toBeInstanceOf(HasMany::class);
    expect($user->tickets->first())->toBeInstanceOf(Ticket::class);
});

it('has many assignedTickets', function (): void {
    $user = User::factory()->create();

    Ticket::factory()->create(['assigned_to' => $user->id]);

    expect($user->assignedTickets())->toBeInstanceOf(HasMany::class);
    expect($user->assignedTickets->first())->toBeInstanceOf(Ticket::class);
});

it('has is tickets admin function', function (): void {
    $user = User::factory()->create();

    expect($user->isTicketsManager())->toBeFalse();

    $role = Role::firstOrCreate(['name' => SupportRoles::Manager->value]);

    $user->assignRole($role);

    expect($user->isTicketsManager())->toBeTrue();
});

it('has is tickets agent function', function (): void {
    $user = User::factory()->create();

    expect($user->isTicketsAgent())->toBeFalse();

    $role = Role::firstOrCreate(['name' => SupportRoles::Agent->value]);

    $user->assignRole($role);

    expect($user->isTicketsAgent())->toBeTrue();
});
