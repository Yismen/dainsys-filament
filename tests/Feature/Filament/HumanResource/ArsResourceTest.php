<?php

use App\Models\Ars;
use App\Models\User;
use App\Models\Permission;
use function Livewire\before;

use Filament\Facades\Filament;
use function Pest\Livewire\livewire;
use function Pest\Laravel\{actingAs, get};
use App\Filament\HumanResource\Resources\Ars\Pages\EditArs;
use App\Filament\HumanResource\Resources\Ars\Pages\ListArs;
use App\Filament\HumanResource\Resources\Ars\Pages\ViewArs;
use App\Filament\HumanResource\Resources\Ars\Pages\CreateArs;

beforeEach(function () {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'), // Where `app` is the ID of the panel you want to test.
    );
    $ars = Ars::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListArs::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateArs::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditArs::getRouteName(),
            'params' => ['record' => $ars->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewArs::getRouteName(),
            'params' => ['record' => $ars->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];
});

it('require users to be authenticated to access Ars resource pages', function (string $method) {
    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index' ,
    'create' ,
    'edit',
    'view',
]);

it('require users to have correct permissions to access Ars resource pages', function (string $method) {
    actingAs(User::factory()->create());

    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));
    $response->assertForbidden();
})->with([
    'index' ,
    'create' ,
    'edit',
    'view',
]);

it('allows super admin users to access Ars resource pages', function (string $method) {
    actingAs($this->createSuperAdminUser());

    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index' ,
    'create' ,
    'edit',
    'view',
]);

it('allow users with correct permissions to access Ars resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions( $this->resource_routes[$method]['permission'], 'Ars'));

    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Ars list page correctly', function () {
    $ars = Ars::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Ars'));

    livewire(ListArs::class)
        ->assertCanSeeTableRecords($ars);
});

test('create Ars page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Ars'));

    $name = 'new ARS';
    livewire(CreateArs::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create');

    $this->assertDatabaseHas('arss', [
        'name' => $name,
    ]);
});

test('edit Ars page works correctly', function () {
    $ars = Ars::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Ars'));

    $newName = 'Updated ARS Name';
    livewire(EditArs::class, ['record' => $ars->getKey()])
        ->fillForm([
            'name' => $newName,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('arss', [
        'id' => $ars->id,
        'name' => $newName,
    ]);
});

test('form validation require fields on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Ars'));

    // Test CreateArs validation
    livewire(CreateArs::class)
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
    // Test EditArs validation
    $ars = Ars::factory()->create();
    livewire(EditArs::class, ['record' => $ars->getKey()])
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

test('Ars name must be unique on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Ars'));

    $existingArs = Ars::factory()->create(['name' => 'Unique ARS']);

    // Test CreateArs uniqueness validation
    livewire(CreateArs::class)
        ->fillForm([
            'name' => 'Unique ARS', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
    // Test EditArs uniqueness validation
    $arsToEdit = Ars::factory()->create(['name' => 'Another ARS']);
    livewire(EditArs::class, ['record' => $arsToEdit->getKey()])
        ->fillForm([
            'name' => 'Unique ARS', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Ars without changing name to trigger uniqueness validation', function () {
    $ars = Ars::factory()->create(['name' => 'Existing ARS']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Ars'));

    livewire(EditArs::class, ['record' => $ars->getKey()])
        ->fillForm([
            'name' => 'Existing ARS', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('arss', [
        'id' => $ars->id,
        'name' => 'Existing ARS',
    ]);
});

it('autofocus the name field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Ars'));

    // Test CreateArs autofocus
    livewire(CreateArs::class)
        ->assertSeeHtml('autofocus');

    // Test EditArs autofocus
    $ars = Ars::factory()->create();
    livewire(EditArs::class, ['record' => $ars->getKey()])
        ->assertSeeHtml('autofocus');
});
