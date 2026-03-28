<?php

use App\Models\Role;
use App\Models\User;

use function Pest\Laravel\actingAs;

function createProjectExecutivePanelUser(string $roleName): User
{
    $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('allows project executive role to access project executives panel', function (): void {
    $user = createProjectExecutivePanelUser('Project Executive Manager');
    actingAs($user);

    $panel = filament()->getPanel('project-executive');

    expect($user->canAccessPanel($panel))->toBeTrue();
});

it('allows project executive agent role to access project executives panel', function (): void {
    $user = createProjectExecutivePanelUser('Project Executive Agent');
    actingAs($user);

    $panel = filament()->getPanel('project-executive');

    expect($user->canAccessPanel($panel))->toBeTrue();
});

it('denies users without project executive roles from project executives panel', function (): void {
    $user = createProjectExecutivePanelUser('Human Resource Manager');
    actingAs($user);

    $panel = filament()->getPanel('project-executive');

    expect($user->canAccessPanel($panel))->toBeFalse();
});
