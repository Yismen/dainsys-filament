<?php

use App\Filament\Admin\Resources\Roles\Pages\EditRole;
use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Models\Role;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $panelId = array_key_first(Filament::getPanels());

    if ($panelId) {
        Filament::setCurrentPanel(Filament::getPanel($panelId));
    }
});

it('returns role permission panel options', function (): void {
    $options = RoleResource::getPermissionPanelOptions();

    expect($options)->toBeArray();
    expect($options)->not->toBeEmpty();
});

it('filters checkbox list options by assignment state', function (): void {
    $reflection = new ReflectionMethod(RoleResource::class, 'filterCheckboxListOptions');
    $reflection->setAccessible(true);

    $options = [
        'permission.a' => 'Permission A',
        'permission.b' => 'Permission B',
    ];

    expect($reflection->invoke(null, $options, null, 'assigned', ['permission.a']))
        ->toBe(['permission.a' => 'Permission A']);

    expect($reflection->invoke(null, $options, null, 'unassigned', ['permission.a']))
        ->toBe(['permission.b' => 'Permission B']);

    expect($reflection->invoke(null, $options, null, 'all', ['permission.a']))
        ->toBe($options);
});

it('filters checkbox list options by panel membership', function (): void {
    $panelId = array_key_first(Filament::getPanels());
    $permissionKeysReflection = new ReflectionMethod(RoleResource::class, 'getPermissionKeysForPanel');
    $permissionKeysReflection->setAccessible(true);

    $permissionKeys = $permissionKeysReflection->invoke(null, $panelId);

    expect($permissionKeys)->toBeArray();
    expect($permissionKeys)->not->toBeEmpty();

    $options = array_merge(
        array_combine($permissionKeys, $permissionKeys),
        ['unknown.permission' => 'Unknown permission']
    );

    $reflection = new ReflectionMethod(RoleResource::class, 'filterCheckboxListOptions');
    $reflection->setAccessible(true);

    $filtered = $reflection->invoke(null, $options, $panelId, 'all', null);

    expect($filtered)->toEqual(array_combine($permissionKeys, $permissionKeys));
});

it('shows permission filters on the role edit page', function (): void {
    $role = Role::create([
        'name' => 'Test Role',
        'guard_name' => 'web',
    ]);

    actingAs($this->createSuperAdminUser());

    livewire(EditRole::class, ['record' => $role->getKey()])
        ->assertSee('Panel')
        ->assertSee('Permission Assignment');
});
