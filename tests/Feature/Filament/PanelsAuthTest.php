<?php

use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;

it('requires authentication to access panels', function () {
    foreach (Filament::getPanels() as $panel) {
        if (in_array($panel->getId(), [
            'blog',
        ])) {
            continue; // Ignore panels that have custom access logic
        }

        $this->get("/{$panel->getId()}")->assertRedirect($panel->getLoginUrl());
    }
});

it('prevents regular users without appropriate roles from accessing panels', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    foreach (Filament::getPanels() as $panel) {
        if (in_array($panel->getId(), [
            'blog',
        ])) {
            continue; // Ignore panels that have custom access logic
        }

        $this->get("/{$panel->getId()}")->assertForbidden();
    }
});

it('allows users with manager roles to access panels', function () {
    $user = User::factory()->create(); // Assign a role that should have access

    $this->actingAs($user);

    foreach (Filament::getPanels() as $panel) {
        $role = str($panel->getId())->kebab()->headline()->toString().' Manager';
        Role::create(['name' => $role]); // Assuming role names follow this pattern
        $user->assignRole($role);

        $this->actingAs($user);

        if (in_array($panel->getId(), [
            'blog',
            'admin',
            'employee',
        ])) {
            continue; // Ignore panels that have custom access logic
        }

        $this->get("/{$panel->getId()}")->assertOk();
    }
});

it('allows users with agent roles to access panels', function () {
    $user = User::factory()->create(); // Assign a role that should have access

    $this->actingAs($user);

    foreach (Filament::getPanels() as $panel) {
        $role = str($panel->getId())->kebab()->headline()->toString().' Agent';
        Role::create(['name' => $role]); // Assuming role names follow this pattern
        $user->assignRole($role);

        $this->actingAs($user);

        if (in_array($panel->getId(), [
            'blog',
            'admin',
            'employee',
        ])) {
            continue; // Ignore panels that have custom access logic
        }

        $this->get("/{$panel->getId()}")->assertOk();
    }
});
