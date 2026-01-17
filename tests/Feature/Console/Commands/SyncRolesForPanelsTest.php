<?php

use App\Console\Commands\SyncRolesForPanels;
use Filament\Facades\Filament;

it('sync default roles and all panels', function () {
    $panels = Filament::getPanels();
    $types = ['manager', 'agent'];

    $roles = [
        ['name' => 'Super Admin', 'guard_name' => 'web'],
    ];

    $panelIDs = array_map(fn ($panel) => $panel->getId(), $panels);
    $panelIDs = \array_filter($panelIDs, fn ($panel) => $panel != 'admin');

    foreach ($panelIDs as $key => $value) {
        foreach ($types as $type) {
            $name = implode(' ', [
                $key,
                $type,
            ]);

            $roles[] = [
                'name' => str($name)->trim(' ')->replace(['_', '-'], ' ')->title()->toString(),
                'guard_name' => 'web',
            ];
        }
    }

    $this->artisan(SyncRolesForPanels::class, ['guard_name' => 'web']);

    foreach ($roles as $role) {
        $this->assertDatabaseHas('roles', $role);
    }
});

it('sync includes super admins and default roles', function () {
    $expectedCount = (\count(Filament::getPanels()) * 2) - 2 + 1;

    $this->artisan(SyncRolesForPanels::class, ['guard_name' => 'web']);

    $this->assertDatabaseHas('roles', ['name' => 'Super Admin']);

    $this->assertDatabaseCount('roles', $expectedCount);
});

it('does not syn roles for admin', function () {
    $this->artisan(SyncRolesForPanels::class, ['guard_name' => 'web']);

    $this->assertDatabaseMissing('roles', [
        'Admin Manager',
    ]);

    $this->assertDatabaseMissing('roles', [
        'Admin Agent',
    ]);
});

it('does not duplciate roles when command is run multiple times', function () {
    $expectedCount = (\count(Filament::getPanels()) * 2) - 2 + 1;

    $this->artisan(SyncRolesForPanels::class, ['guard_name' => 'web']);
    $this->artisan(SyncRolesForPanels::class, ['guard_name' => 'web']);
    $this->artisan(SyncRolesForPanels::class, ['guard_name' => 'web']);

    $this->assertDatabaseCount('roles', $expectedCount);
});
