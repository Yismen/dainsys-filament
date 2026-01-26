<?php

use App\Filament\HumanResource\Resources\Supervisors\Pages\CreateSupervisor;
use App\Filament\HumanResource\Resources\Supervisors\Pages\EditSupervisor;
use App\Filament\HumanResource\Resources\Supervisors\Pages\ListSupervisors;
use App\Filament\HumanResource\Resources\Supervisors\Pages\ViewSupervisor;
use App\Models\Supervisor;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

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

    $this->form_data = [
        'user_id' => User::factory()->create()->id,
        'name' => 'new Supervisor',
    ];
});

it('require users to be authenticated to access Supervisor resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Supervisor resource pages', function (string $method) {
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

it('allows super admin users to access Supervisor resource pages', function (string $method) {
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

it('allow users with correct permissions to access Supervisor resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Supervisor'));

    $response = get(route($this->resource_routes[$method]['route'],
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

    livewire(CreateSupervisor::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('supervisors', [
        'name' => $this->form_data['name'],
        'user_id' => $this->form_data['user_id'],
    ]);
});

test('edit Supervisor page works correctly', function () {
    $supervisor = Supervisor::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Supervisor'));

    $newName = 'Updated Supervisor Name';
    livewire(EditSupervisor::class, ['record' => $supervisor->getKey()])
        ->fillForm([
            'user_id' => $supervisor->user_id,
            'name' => $newName,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('supervisors', [
        'id' => $supervisor->id,
        'name' => $newName,
        'user_id' => $supervisor->user_id,
    ]);
});

test('form validation require fields on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Supervisor'));

    // Test CreateSupervisor validation
    livewire(CreateSupervisor::class)
        ->fillForm([
            'user_id' => null,
            'name' => '', // Invalid: name is required
        ])
        ->call('create')
        ->assertHasFormErrors([
            'user_id' => 'required',
            'name' => 'required',
        ]);
    // Test EditSupervisor validation
    $supervisor = Supervisor::factory()->create();
    livewire(EditSupervisor::class, ['record' => $supervisor->getKey()])
        ->fillForm([
            'user_id' => null,
            'name' => '', // Invalid: name is required
        ])
        ->call('save')
        ->assertHasFormErrors([
            'user_id' => 'required',
            'name' => 'required',
        ]);
});

test('Supervisor name must be unique on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Supervisor'));

    $existingSupervisor = Supervisor::factory()->create(['name' => 'Unique Supervisor']);

    // Test CreateSupervisor uniqueness validation
    livewire(CreateSupervisor::class)
        ->fillForm([
            'user_id' => User::factory()->create()->id,
            'name' => 'Unique Supervisor', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
    // Test EditSupervisor uniqueness validation
    $supervisorToEdit = Supervisor::factory()->create(['name' => 'Another Supervisor']);
    livewire(EditSupervisor::class, ['record' => $supervisorToEdit->getKey()])
        ->fillForm([
            'user_id' => $supervisorToEdit->user_id,
            'name' => 'Unique Supervisor', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

test('Supervisor user must be unique on create', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Supervisor'));

    $user = User::factory()->create();
    Supervisor::factory()->create([
        'user_id' => $user->id,
    ]);

    livewire(CreateSupervisor::class)
        ->fillForm([
            'user_id' => $user->id,
            'name' => 'Second Supervisor',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'user_id' => 'unique',
        ]);
});

it('allows updating Supervisor without changing name to trigger uniqueness validation', function () {
    $supervisor = Supervisor::factory()->create(['name' => 'Existing Supervisor']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Supervisor'));

    livewire(EditSupervisor::class, ['record' => $supervisor->getKey()])
        ->fillForm([
            'user_id' => $supervisor->user_id,
            'name' => 'Existing Supervisor', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('supervisors', [
        'id' => $supervisor->id,
        'name' => 'Existing Supervisor',
        'user_id' => $supervisor->user_id,
    ]);
});

it('autofocus the name field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Supervisor'));

    // Test CreateSupervisor autofocus
    livewire(CreateSupervisor::class)
        ->assertSeeHtml('autofocus');

    // Test EditSupervisor autofocus
    $supervisor = Supervisor::factory()->create();
    livewire(EditSupervisor::class, ['record' => $supervisor->getKey()])
        ->assertSeeHtml('autofocus');
});
