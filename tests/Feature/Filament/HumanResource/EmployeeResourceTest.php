<?php

use App\Enums\EmployeeStatuses;
use App\Enums\Genders;
use App\Enums\PersonalIdTypes;
use App\Models\Employee;
use App\Models\User;
use App\Models\Permission;
use function Livewire\before;

use Filament\Facades\Filament;
use function Pest\Livewire\livewire;
use function Pest\Laravel\{actingAs, get};
use App\Filament\HumanResource\Resources\Employees\Pages\EditEmployee;
use App\Filament\HumanResource\Resources\Employees\Pages\ListEmployees;
use App\Filament\HumanResource\Resources\Employees\Pages\CreateEmployee;
use App\Filament\HumanResource\Resources\Employees\Pages\ViewEmployee;
use App\Models\Citizenship;

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
});

it('require users to be authenticated to access Employee resource pages', function (string $method) {
    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index' ,
    'create' ,
    'edit',
    'view',
]);

it('require users to have correct permissions to access Employee resource pages', function (string $method) {
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

it('allows super admin users to access Employee resource pages', function (string $method) {
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

it('allow users with correct permissions to access Employee resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions( $this->resource_routes[$method]['permission'], 'Employee'));

    $response = get(route( $this->resource_routes[$method]['route'],
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

    $first_name = 'first name';
    $second_first_name = 'second first name';
    $last_name = 'last name';
    $second_last_name = 'second last name';
    $personal_id_type = PersonalIdTypes::DominicanId->value;
    $personal_id = '12345678999';
    $date_of_birth = '1990-01-01';
    $cellphone = '8091234567';
    $gender = Genders::Male->value;
    $has_kids = true;
    $citizenship_id = Citizenship::factory()->create()->id;
    livewire(CreateEmployee::class)
        ->fillForm([
            'first_name' => $first_name,
            'second_first_name' => $second_first_name,
            'last_name' => $last_name,
            'second_last_name' => $second_last_name,
            'personal_id_type' => $personal_id_type,
            'personal_id' => $personal_id,
            'date_of_birth' => $date_of_birth,
            'cellphone' => $cellphone,
            'gender' => $gender,
            'has_kids' => $has_kids,
            'citizenship_id' => $citizenship_id,
        ])
        ->call('create');

    $this->assertDatabaseHas('employees', [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'personal_id_type' => $personal_id_type,
        'personal_id' => $personal_id,
        'date_of_birth' => $date_of_birth,
        'cellphone' => $cellphone,
        'gender' => $gender,
        'has_kids' => $has_kids,
        'citizenship_id' => $citizenship_id,
    ]);
});

test('edit Employee page works correctly', function () {
    $employee = Employee::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Employee'));

    $newFirstName = 'Updated Employee Name';
    $newSecondFirstName = 'Updated Second First Name';
    $newLastName = 'Updated Last Name';
    $newSecondLastName = 'Updated Second Last Name';
    $newPersonalIdType = PersonalIdTypes::Passport->value;
    $newPersonalId = '98765432100';
    $newDateOfBirth = '1985-05-15';
    $newCellphone = '8297654321';
    $newGender = Genders::Female->value;
    $newHasKids = false;
    $newCitizenshipId = Citizenship::factory()->create()->id;

    livewire(EditEmployee::class, ['record' => $employee->getKey()])
        ->fillForm([
            'first_name' => $newFirstName,
            'second_first_name' => $newSecondFirstName,
            'last_name' => $newLastName,
            'second_last_name' => $newSecondLastName,
            'personal_id_type' => $newPersonalIdType,
            'personal_id' => $newPersonalId,
            'date_of_birth' => $newDateOfBirth,
            'cellphone' => $newCellphone,
            'gender' => $newGender,
            'has_kids' => $newHasKids,
            'citizenship_id' => $newCitizenshipId,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('employees', [
        'id' => $employee->id,
        'first_name' => $newFirstName,
        'second_first_name' => $newSecondFirstName,
        'last_name' => $newLastName,
        'second_last_name' => $newSecondLastName,
        'personal_id_type' => $newPersonalIdType,
        'personal_id' => $newPersonalId,
        'date_of_birth' => $newDateOfBirth,
        'cellphone' => $newCellphone,
        'gender' => $newGender,
        'has_kids' => $newHasKids,
        'citizenship_id' => $newCitizenshipId,
    ]);
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
            'citizenship_id' => 'required'
        ]);
});

test('Employee name must be unique on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Employee'));

    $unique_personal_id = '15166635118';
    $unique_cellphone = '8095551234';

    $existingEmployee = Employee::factory()->create([
        'personal_id' => $unique_personal_id,
        'cellphone' => $unique_cellphone,
    ]);

    // Test CreateEmployee uniqueness validation
    livewire(CreateEmployee::class)
        ->fillForm([
            'personal_id' => $unique_personal_id, // Invalid: name must be unique
            'cellphone' => $unique_cellphone,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'personal_id' => 'unique',
            'cellphone' => 'unique'
        ]);
    // Test EditEmployee uniqueness validation
    $employeeToEdit = Employee::factory()->create(['personal_id' => '33333333333', 'cellphone' => '8097778888']);
    livewire(EditEmployee::class, ['record' => $employeeToEdit->getKey()])
        ->fillForm([
            'personal_id' => $unique_personal_id, // Invalid: name must be unique
            'cellphone' => $unique_cellphone,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'personal_id' => 'unique',
            'cellphone' => 'unique'
        ]);
});

it('allows updating Employee without changing name to trigger uniqueness validation', function () {
    $employee = Employee::factory()->create(['personal_id' => '33333333333', 'cellphone' => '8097778888']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Employee'));

    livewire(EditEmployee::class, ['record' => $employee->getKey()])
        ->fillForm([
            'personal_id' => '33333333333', // Same personal_id, should not trigger uniqueness error
            'cellphone' => '8097778888', // Same cellphone, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('employees', [
        'id' => $employee->id,
        'personal_id' => '33333333333',
        'cellphone' => '8097778888',
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
