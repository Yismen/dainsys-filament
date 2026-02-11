<?php

use App\Filament\HumanResource\Resources\SuspensionTypes\Pages\CreateSuspensionType;
use App\Filament\HumanResource\Resources\SuspensionTypes\Pages\EditSuspensionType;
use App\Filament\HumanResource\Resources\SuspensionTypes\Pages\ListSuspensionTypes;
use App\Filament\HumanResource\Resources\SuspensionTypes\Pages\ViewSuspensionType;
use App\Models\SuspensionType;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'), // Where `app` is the ID of the panel you want to test.
    );
    $suspension_type = SuspensionType::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListSuspensionTypes::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateSuspensionType::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditSuspensionType::getRouteName(),
            'params' => ['record' => $suspension_type->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewSuspensionType::getRouteName(),
            'params' => ['record' => $suspension_type->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];
});

it('require users to be authenticated to access SuspensionType resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access SuspensionType resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));
    $response->assertForbidden();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('allows super admin users to access SuspensionType resource pages', function (string $method): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('allow users with correct permissions to access SuspensionType resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'SuspensionType'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays SuspensionType list page correctly', function (): void {
    $suspension_types = SuspensionType::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any SuspensionType'));

    livewire(ListSuspensionTypes::class)
        ->assertCanSeeTableRecords($suspension_types);
});

test('create SuspensionType page works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'SuspensionType'));

    $name = 'new SuspensionType';
    livewire(CreateSuspensionType::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create');

    $this->assertDatabaseHas('suspension_types', [
        'name' => $name,
    ]);
});

test('edit SuspensionType page works correctly', function (): void {
    $suspension_type = SuspensionType::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'SuspensionType'));

    $newName = 'Updated SuspensionType Name';
    livewire(EditSuspensionType::class, ['record' => $suspension_type->getKey()])
        ->fillForm([
            'name' => $newName,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('suspension_types', [
        'id' => $suspension_type->id,
        'name' => $newName,
    ]);
});

test('form validation require fields on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'SuspensionType'));

    // Test CreateSuspensionType validation
    livewire(CreateSuspensionType::class)
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
    // Test EditSuspensionType validation
    $suspension_type = SuspensionType::factory()->create();
    livewire(EditSuspensionType::class, ['record' => $suspension_type->getKey()])
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

test('SuspensionType name must be unique on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'SuspensionType'));

    $existingSuspensionType = SuspensionType::factory()->create(['name' => 'Unique SuspensionType']);

    // Test CreateSuspensionType uniqueness validation
    livewire(CreateSuspensionType::class)
        ->fillForm([
            'name' => 'Unique SuspensionType', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
    // Test EditSuspensionType uniqueness validation
    $suspension_typeToEdit = SuspensionType::factory()->create(['name' => 'Another SuspensionType']);
    livewire(EditSuspensionType::class, ['record' => $suspension_typeToEdit->getKey()])
        ->fillForm([
            'name' => 'Unique SuspensionType', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating SuspensionType without changing name to trigger uniqueness validation', function (): void {
    $suspension_type = SuspensionType::factory()->create(['name' => 'Existing SuspensionType']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'SuspensionType'));

    livewire(EditSuspensionType::class, ['record' => $suspension_type->getKey()])
        ->fillForm([
            'name' => 'Existing SuspensionType', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('suspension_types', [
        'id' => $suspension_type->id,
        'name' => 'Existing SuspensionType',
    ]);
});

it('autofocus the name field on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'SuspensionType'));

    // Test CreateSuspensionType autofocus
    livewire(CreateSuspensionType::class)
        ->assertSeeHtml('autofocus');

    // Test EditSuspensionType autofocus
    $suspension_type = SuspensionType::factory()->create();
    livewire(EditSuspensionType::class, ['record' => $suspension_type->getKey()])
        ->assertSeeHtml('autofocus');
});
