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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
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
        // 'create' => [
        //     'route' => CreateEmployee::getRouteName(),
        //     'params' => [],
        //     'permission' => ['create', 'view-any'],
        // ],
        // 'edit' => [
        //     'route' => EditEmployee::getRouteName(),
        //     'params' => ['record' => $employee->getKey()],
        //     'permission' => ['update', 'edit', 'view-any'],
        // ],
        // 'view' => [
        //     'route' => ViewEmployee::getRouteName(),
        //     'params' => ['record' => $employee->getKey()],
        //     'permission' => ['view', 'view-any'],
        // ],
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

it('require users to be authenticated to access Employee resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('require users to have correct permissions to access Employee resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));
    $response->assertForbidden();
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('allows super admin users to access Employee resource pages', function (string $method): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('allow users with correct permissions to access Employee resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Employee'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('displays Employee list page correctly', function (): void {
    $employees = Employee::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Employee'));

    livewire(ListEmployees::class)
        ->assertCanSeeTableRecords($employees);
});
