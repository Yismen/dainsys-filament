<?php

use App\Filament\HumanResource\Resources\Departments\Pages\CreateDepartment;
use App\Filament\HumanResource\Resources\Departments\Pages\EditDepartment;
use App\Filament\HumanResource\Resources\Departments\Pages\ListDepartments;
use App\Filament\HumanResource\Resources\Departments\Pages\ViewDepartment;
use App\Models\Department;
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
    $department = Department::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListDepartments::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateDepartment::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditDepartment::getRouteName(),
            'params' => ['record' => $department->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewDepartment::getRouteName(),
            'params' => ['record' => $department->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];
});

it('require users to be authenticated to access Department resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Department resource pages', function (string $method) {
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

it('allows super admin users to access Department resource pages', function (string $method) {
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

it('allow users with correct permissions to access Department resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Department'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Department list page correctly', function () {
    $departments = Department::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Department'));

    livewire(ListDepartments::class)
        ->assertCanSeeTableRecords($departments);
});

test('create Department page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Department'));

    $name = 'new Department';
    livewire(CreateDepartment::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create');

    $this->assertDatabaseHas('departments', [
        'name' => $name,
    ]);
});

test('edit Department page works correctly', function () {
    $department = Department::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Department'));

    $newName = 'Updated Department Name';
    livewire(EditDepartment::class, ['record' => $department->getKey()])
        ->fillForm([
            'name' => $newName,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('departments', [
        'id' => $department->id,
        'name' => $newName,
    ]);
});

test('form validation require fields on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Department'));

    // Test CreateDepartment validation
    livewire(CreateDepartment::class)
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
    // Test EditDepartment validation
    $department = Department::factory()->create();
    livewire(EditDepartment::class, ['record' => $department->getKey()])
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

test('Department name must be unique on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Department'));

    $existingDepartment = Department::factory()->create(['name' => 'Unique Department']);

    // Test CreateDepartment uniqueness validation
    livewire(CreateDepartment::class)
        ->fillForm([
            'name' => 'Unique Department', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
    // Test EditDepartment uniqueness validation
    $departmentToEdit = Department::factory()->create(['name' => 'Another Department']);
    livewire(EditDepartment::class, ['record' => $departmentToEdit->getKey()])
        ->fillForm([
            'name' => 'Unique Department', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Department without changing name to trigger uniqueness validation', function () {
    $department = Department::factory()->create(['name' => 'Existing Department']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Department'));

    livewire(EditDepartment::class, ['record' => $department->getKey()])
        ->fillForm([
            'name' => 'Existing Department', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('departments', [
        'id' => $department->id,
        'name' => 'Existing Department',
    ]);
});

it('autofocus the name field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Department'));

    // Test CreateDepartment autofocus
    livewire(CreateDepartment::class)
        ->assertSeeHtml('autofocus');

    // Test EditDepartment autofocus
    $department = Department::factory()->create();
    livewire(EditDepartment::class, ['record' => $department->getKey()])
        ->assertSeeHtml('autofocus');
});
