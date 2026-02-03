<?php

use App\Enums\Genders;
use App\Enums\PersonalIdTypes;
use App\Filament\HumanResource\Resources\Employees\Pages\CreateEmployee;
use App\Filament\HumanResource\Resources\Employees\Pages\EditEmployee;
use App\Filament\HumanResource\Resources\Employees\Pages\ListEmployees;
use App\Filament\HumanResource\Resources\Employees\Pages\ViewEmployee;
use App\Models\Citizenship;
use App\Models\Employee;
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
    $employee = Employee::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListEmployees::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateEmployee::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditEmployee::getRouteName(),
            'params' => ['record' => $employee->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewEmployee::getRouteName(),
            'params' => ['record' => $employee->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];

    $this->form_data = [
        'first_name' => 'first name',
        'second_first_name' => 'second first name',
        'last_name' => 'last name',
        'second_last_name' => 'second last name',
        'personal_id_type' => PersonalIdTypes::DominicanId->value,
        'personal_id' => '12345678999',
        'date_of_birth' => '1990-01-01',
        'cellphone' => '8091234567',
        'secondary_phone' => '8091234567',
        'email' => 'test@mail.com',
        'address' => 'calle tu sabe',
        'gender' => Genders::Male->value,
        'has_kids' => true,
        'citizenship_id' => Citizenship::factory()->create()->id,
        'internal_id' => null,
    ];
});

it('require users to be authenticated to access Employee resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Employee resource pages', function (string $method) {
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

it('allows super admin users to access Employee resource pages', function (string $method) {
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

it('allow users with correct permissions to access Employee resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Employee'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Employee list page correctly', function () {
    $employees = Employee::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Employee'));

    livewire(ListEmployees::class)
        ->assertCanSeeTableRecords($employees);
});

test('create Employee page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Employee'));

    livewire(CreateEmployee::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('employees', $this->form_data);
});

test('edit Employee page works correctly', function () {
    $employee = Employee::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Employee'));

    livewire(EditEmployee::class, ['record' => $employee->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $this->form_data['id'] = $employee->id;
    $this->assertDatabaseHas('employees', $this->form_data);
});

test('form validation require fields on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Employee'));

    // Test CreateEmployee validation
    livewire(CreateEmployee::class)
        ->fillForm([
            'first_name' => '',
            'last_name' => '',
            'personal_id_type' => '',
            'personal_id' => '',
            'date_of_birth' => '',
            'cellphone' => '',
            'gender' => '',
            'has_kids' => null,
            'citizenship_id' => '',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'first_name' => 'required',
            'last_name' => 'required',
            'personal_id_type' => 'required',
            'personal_id' => 'required',
            'date_of_birth' => 'required',
            'cellphone' => 'required',
            'gender' => 'required',
            'has_kids' => 'required',
            'citizenship_id' => 'required']);
    // Test EditEmployee validation
    $employee = Employee::factory()->create();
    livewire(EditEmployee::class, ['record' => $employee->getKey()])
        ->fillForm([
            'first_name' => '', // Invalid: name is required
            'last_name' => '',
            'personal_id_type' => '',
            'personal_id' => '',
            'date_of_birth' => '',
            'cellphone' => '',
            'gender' => '',
            'has_kids' => null,
            'citizenship_id' => '',
        ])
        ->call('save')
        ->assertHasFormErrors([
            'first_name' => 'required',
            'last_name' => 'required',
            'personal_id_type' => 'required',
            'personal_id' => 'required',
            'date_of_birth' => 'required',
            'cellphone' => 'required',
            'gender' => 'required',
            'has_kids' => 'required',
            'citizenship_id' => 'required',
        ]);
});

test('Employee fields must be unique on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Employee'));

    $unique_personal_id = '15166635118';
    $unique_cellphone = '8095551234';
    $unique_internal_id = '3333';

    $existingEmployee = Employee::factory()->create([
        'personal_id' => $unique_personal_id,
        'cellphone' => $unique_cellphone,
        'internal_id' => $unique_internal_id,
    ]);

    // Test CreateEmployee uniqueness validation
    livewire(CreateEmployee::class)
        ->fillForm([
            'personal_id' => $unique_personal_id, // Invalid: personal_id must be unique
            'cellphone' => $unique_cellphone,
            'internal_id' => $unique_internal_id,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'personal_id' => 'unique',
            'cellphone' => 'unique',
            // 'internal_id' => 'unique',
        ]);
    // Test EditEmployee uniqueness validation
    $employeeToEdit = Employee::factory()->create(['personal_id' => '33333333333', 'cellphone' => '8097778888']);
    livewire(EditEmployee::class, ['record' => $employeeToEdit->getKey()])
        ->fillForm([
            'personal_id' => $unique_personal_id, // Invalid: personal_id must be unique
            'cellphone' => $unique_cellphone,
            'internal_id' => $unique_internal_id,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'personal_id' => 'unique',
            'cellphone' => 'unique',
            'internal_id' => 'unique',
        ]);
});

it('allows updating Employee without changing name to trigger uniqueness validation', function () {
    $employee = Employee::factory()->create(['personal_id' => '33333333333', 'cellphone' => '8097778888']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Employee'));

    livewire(EditEmployee::class, ['record' => $employee->getKey()])
        ->fillForm([
            'personal_id' => '33333333333', // Same personal_id, should not trigger uniqueness error
            'cellphone' => '8097778888', // Same cellphone, should not trigger uniqueness error
            'internal_id' => '7777', // Same internal_id, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('employees', [
        'id' => $employee->id,
        'personal_id' => '33333333333',
        'cellphone' => '8097778888',
        'internal_id' => '7777',
    ]);
});

it('autofocus the name field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Employee'));

    // Test CreateEmployee autofocus
    livewire(CreateEmployee::class)
        ->assertSeeHtml('autofocus');

    // Test EditEmployee autofocus
    $employee = Employee::factory()->create();
    livewire(EditEmployee::class, ['record' => $employee->getKey()])
        ->assertSeeHtml('autofocus');
});
