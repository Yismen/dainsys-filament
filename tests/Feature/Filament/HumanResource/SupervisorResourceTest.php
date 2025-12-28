<?php

use App\Models\User;
use App\Models\Permission;
use App\Models\Supervisor;
use Spatie\FlareClient\View;

use function Livewire\before;
use Filament\Facades\Filament;
use GuzzleHttp\Promise\Create;
use function Pest\Livewire\livewire;
use function Pest\Laravel\{actingAs, get};
use App\Filament\HumanResource\Resources\Supervisors\Pages\EditSupervisor;
use App\Filament\HumanResource\Resources\Supervisors\Pages\ViewSupervisor;
use App\Filament\HumanResource\Resources\Supervisors\Pages\ListSupervisors;
use App\Filament\HumanResource\Resources\Supervisors\Pages\CreateSupervisor;

beforeEach(function () {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'), // Where `app` is the ID of the panel you want to test.
    );
    $supervisor = Supervisor::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListSupervisors::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateSupervisor::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditSupervisor::getRouteName(),
            'params' => ['record' => $supervisor->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewSupervisor::getRouteName(),
            'params' => ['record' => $supervisor->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];
});

it('require users to be authenticated to access Supervisor resource pages', function (string $method) {
    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index' ,
    'create' ,
    'edit',
    'view',
]);

it('require users to have correct permissions to access Supervisor resource pages', function (string $method) {
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

it('allows super admin users to access Supervisor resource pages', function (string $method) {
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

it('allow users with correct permissions to access Supervisor resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions( $this->resource_routes[$method]['permission'], 'Supervisor'));

    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Supervisor list page correctly', function () {
    $supervisors = Supervisor::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Supervisor'));

    livewire(ListSupervisors::class)
        ->assertCanSeeTableRecords($supervisors);
});

test('create Supervisor page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Supervisor'));

    $name = 'new AFP';
    livewire(CreateSupervisor::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create');

    $this->assertDatabaseHas('supervisors', [
        'name' => $name,
    ]);
});

test('edit Supervisor page works correctly', function () {
    $supervisor = Supervisor::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Supervisor'));

    $newName = 'Updated AFP Name';
    livewire(EditSupervisor::class, ['record' => $supervisor->getKey()])
        ->fillForm([
            'name' => $newName,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('supervisors', [
        'id' => $supervisor->id,
        'name' => $newName,
    ]);
});

test('form validation require fields on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Supervisor'));

    // Test CreateSupervisor validation
    livewire(CreateSupervisor::class)
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
    // Test EditSupervisor validation
    $supervisor = Supervisor::factory()->create();
    livewire(EditSupervisor::class, ['record' => $supervisor->getKey()])
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

test('Supervisor name must be unique on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Supervisor'));

    $existingSupervisor = Supervisor::factory()->create(['name' => 'Unique AFP']);

    // Test CreateSupervisor uniqueness validation
    livewire(CreateSupervisor::class)
        ->fillForm([
            'name' => 'Unique AFP', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
    // Test EditSupervisor uniqueness validation
    $supervisorToEdit = Supervisor::factory()->create(['name' => 'Another AFP']);
    livewire(EditSupervisor::class, ['record' => $supervisorToEdit->getKey()])
        ->fillForm([
            'name' => 'Unique AFP', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Supervisor without changing name to trigger uniqueness validation', function () {
    $supervisor = Supervisor::factory()->create(['name' => 'Existing AFP']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Supervisor'));

    livewire(EditSupervisor::class, ['record' => $supervisor->getKey()])
        ->fillForm([
            'name' => 'Existing AFP', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('supervisors', [
        'id' => $supervisor->id,
        'name' => 'Existing AFP',
    ]);
});
