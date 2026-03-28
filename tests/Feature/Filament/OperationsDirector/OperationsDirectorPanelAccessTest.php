<?php

use App\Models\Role;
use App\Models\User;

use function Pest\Laravel\actingAs;

function createOperationsDirectorPanelUser(string $roleName): User
{
    $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('allows operations director manager role to access operations directors panel', function (): void {
    $user = createOperationsDirectorPanelUser('Operations Director Manager');
    actingAs($user);

    $panel = filament()->getPanel('operations-director');

    expect($user->canAccessPanel($panel))->toBeTrue();
});

it('allows operations director agent role to access operations directors panel', function (): void {
    $user = createOperationsDirectorPanelUser('Operations Director Agent');
    actingAs($user);

    $panel = filament()->getPanel('operations-director');

    expect($user->canAccessPanel($panel))->toBeTrue();
});

it('denies users without operations director roles from operations directors panel', function (): void {
    $user = createOperationsDirectorPanelUser('Human Resource Manager');
    actingAs($user);

    $panel = filament()->getPanel('operations-director');

    expect($user->canAccessPanel($panel))->toBeFalse();
});
