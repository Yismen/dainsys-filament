<?php

use App\Enums\SalaryTypes;
use App\Models\User;
use App\Models\Permission;
use App\Models\Position;
use Spatie\FlareClient\View;

use function Livewire\before;
use Filament\Facades\Filament;
use GuzzleHttp\Promise\Create;
use function Pest\Livewire\livewire;
use function Pest\Laravel\{actingAs, get};
use App\Filament\HumanResource\Resources\Positions\Pages\EditPosition;
use App\Filament\HumanResource\Resources\Positions\Pages\ViewPosition;
use App\Filament\HumanResource\Resources\Positions\Pages\ListPositions;
use App\Filament\HumanResource\Resources\Positions\Pages\CreatePosition;
use App\Models\Department;

beforeEach(function () {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'), // Where `app` is the ID of the panel you want to test.
    );
    $position = Position::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListPositions::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreatePosition::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditPosition::getRouteName(),
            'params' => ['record' => $position->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewPosition::getRouteName(),
            'params' => ['record' => $position->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];
});

it('require users to be authenticated to access Position resource pages', function (string $method) {
    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index' ,
    'create' ,
    'edit',
    'view',
]);

it('require users to have correct permissions to access Position resource pages', function (string $method) {
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

it('allows super admin users to access Position resource pages', function (string $method) {
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

it('allow users with correct permissions to access Position resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions( $this->resource_routes[$method]['permission'], 'Position'));

    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Position list page correctly', function () {
    $positions = Position::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Position'));

    livewire(ListPositions::class)
        ->assertCanSeeTableRecords($positions);
});

test('create Position page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Position'));

    $name = 'new Position';
    livewire(CreatePosition::class)
        ->fillForm([
            'name' => $name,
            'department_id' => Department::factory()->create()->id,
            'salary_type' => SalaryTypes::Salary->value,
            'salary' => 50000,
        ])
        ->call('create');

    $this->assertDatabaseHas('positions', [
        'name' => $name,
    ]);
});

test('edit Position page works correctly', function () {
    $position = Position::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Position'));

    $newName = 'Updated Position Name';
    livewire(EditPosition::class, ['record' => $position->getKey()])
        ->fillForm([
            'name' => $newName,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('positions', [
        'id' => $position->id,
        'name' => $newName,
    ]);
});

test('form validation require fields on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Position'));

    // Test CreatePosition validation
    livewire(CreatePosition::class)
        ->fillForm([
            'name' => '', // Invalid: name is required
            'department_id' => '', // Invalid: department_id is required
            'salary_type' => '', // Invalid: salary_type is required
            'salary' => '', // Invalid: salary is required
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'department_id' => 'required',
            'salary_type' => 'required',
            'salary' => 'required'
        ]);
    // Test EditPosition validation
    $position = Position::factory()->create();
    livewire(EditPosition::class, ['record' => $position->getKey()])
        ->fillForm([
            'name' => '', // Invalid: name is required
            'department_id' => '', // Invalid: department_id is required
            'salary_type' => '', // Invalid: salary_type is required
            'salary' => '', // Invalid: salary is required
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

test('Position name must be unique on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Position'));

    $existingPosition = Position::factory()->create(['name' => 'Unique Position']);

    // Test CreatePosition uniqueness validation
    livewire(CreatePosition::class)
        ->fillForm([
            'name' => 'Unique Position', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
    // Test EditPosition uniqueness validation
    $positionToEdit = Position::factory()->create(['name' => 'Another Position']);
    livewire(EditPosition::class, ['record' => $positionToEdit->getKey()])
        ->fillForm([
            'name' => 'Unique Position', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Position without changing name to trigger uniqueness validation', function () {
    $position = Position::factory()->create(['name' => 'Existing Position']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Position'));

    livewire(EditPosition::class, ['record' => $position->getKey()])
        ->fillForm([
            'name' => 'Existing Position', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('positions', [
        'id' => $position->id,
        'name' => 'Existing Position',
    ]);
});

it('autofocus the name field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Position'));

    // Test CreatePosition autofocus
    livewire(CreatePosition::class)
        ->assertSeeHtml('autofocus');

    // Test EditPosition autofocus
    $position = Position::factory()->create();
    livewire(EditPosition::class, ['record' => $position->getKey()])
        ->assertSeeHtml('autofocus');
});
