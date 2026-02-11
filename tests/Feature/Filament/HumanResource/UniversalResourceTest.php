<?php

use App\Filament\HumanResource\Resources\Universals\Pages\CreateUniversal;
use App\Filament\HumanResource\Resources\Universals\Pages\EditUniversal;
use App\Filament\HumanResource\Resources\Universals\Pages\ListUniversals;
use App\Filament\HumanResource\Resources\Universals\Pages\ViewUniversal;
use App\Models\Employee;
use App\Models\Universal;
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
    $universal = Universal::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListUniversals::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateUniversal::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditUniversal::getRouteName(),
            'params' => ['record' => $universal->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewUniversal::getRouteName(),
            'params' => ['record' => $universal->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];
});

it('require users to be authenticated to access Universal resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Universal resource pages', function (string $method): void {
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

it('allows super admin users to access Universal resource pages', function (string $method): void {
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

it('allow users with correct permissions to access Universal resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Universal'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Universal list page correctly', function (): void {
    $universals = Universal::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Universal'));

    livewire(ListUniversals::class)
        ->assertCanSeeTableRecords($universals);
});

test('create Universal page works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Universal'));

    $date_since = '2024-01-01';
    livewire(CreateUniversal::class)
        ->fillForm([
            'date_since' => $date_since,
            'employee_id' => Employee::factory()->create()->id,
        ])
        ->call('create');

    $this->assertDatabaseHas('universals', [
        'date_since' => $date_since,
    ]);
});

test('edit Universal page works correctly', function (): void {
    $universal = Universal::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Universal'));

    $newDate = '2024-02-02';
    livewire(EditUniversal::class, ['record' => $universal->getKey()])
        ->fillForm([
            'date_since' => $newDate,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('universals', [
        'id' => $universal->id,
        'date_since' => $newDate,
    ]);
});

test('form validation require fields on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Universal'));

    // Test CreateUniversal validation
    livewire(CreateUniversal::class)
        ->fillForm([
            'date_since' => '', // Invalid: name is required
            'employee_id' => '', // Invalid: employee_id is required
        ])
        ->call('create')
        ->assertHasFormErrors([
            'date_since' => 'required',
            'employee_id' => 'required',
        ]);
    // Test EditUniversal validation
    $universal = Universal::factory()->create();
    livewire(EditUniversal::class, ['record' => $universal->getKey()])
        ->fillForm([
            'date_since' => '', // Invalid: name is required
            'employee_id' => '', // Invalid: employee_id is required
        ])
        ->call('save')
        ->assertHasFormErrors(['date_since' => 'required']);
});

test('Universal name must be unique on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Universal'));
    $employee = Employee::factory()->create();

    $existingUniversal = Universal::factory()->create(['employee_id' => $employee->id]);

    // Test CreateUniversal uniqueness validation
    livewire(CreateUniversal::class)
        ->fillForm([
            'employee_id' => $employee->id, // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'unique']);
    // Test EditUniversal uniqueness validation
    $universalToEdit = Universal::factory()->create(['employee_id' => $employee->id]);
    livewire(EditUniversal::class, ['record' => $universalToEdit->getKey()])
        ->fillForm([
            'employee_id' => $employee->id, // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['employee_id' => 'unique']);
});

it('allows updating Universal without changing name to trigger uniqueness validation', function (): void {
    $employee = Employee::factory()->create();
    $universal = Universal::factory()->create(['employee_id' => $employee->id]);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Universal'));

    livewire(EditUniversal::class, ['record' => $universal->getKey()])
        ->fillForm([
            'employee_id' => $employee->id, // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('universals', [
        'id' => $universal->id,
        'employee_id' => $employee->id,
    ]);
});
