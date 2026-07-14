<?php

use App\Enums\Genders;
use App\Enums\PersonalIdTypes;
use App\Filament\HumanResource\Resources\Employees\Pages\ListEmployees;
use App\Models\Citizenship;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Mail;

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
        'extra_attributes' => [
            'department_code' => 'IT-001',
            'shift' => 'Night',
        ],
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

it('loads human resource employee edit action modal when position details are null', function (): void {
    $position = Position::factory()->create();
    $position->forceFill(['details' => null])->saveQuietly();

    $employee = Employee::factory()->create([
        'site_id' => Site::factory(),
        'project_id' => Project::factory(),
        'position_id' => $position->id,
        'supervisor_id' => Supervisor::factory(),
        'hired_at' => now(),
    ]);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Employee'));

    livewire(ListEmployees::class)
        ->mountTableAction('edit', $employee->getKey())
        ->assertOk();
});

it('keeps hired assignment data when saving the edit modal after hiring from footer action', function (): void {
    Mail::fake();

    $site = Site::factory()->create();
    $project = Project::factory()->create();
    $position = Position::factory()->create();
    $supervisor = Supervisor::factory()->create();
    $hiredAt = now()->subDay()->startOfMinute();

    $employee = Employee::factory()->create([
        'site_id' => null,
        'project_id' => null,
        'position_id' => null,
        'supervisor_id' => null,
        'hired_at' => null,
        'internal_id' => null,
    ]);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Employee'));

    livewire(ListEmployees::class)
        ->callAction(['edit', 'hire'], $employee->getKey(), [
            'site_id' => $site->id,
            'project_id' => $project->id,
            'position_id' => $position->id,
            'supervisor_id' => $supervisor->id,
            'hired_at' => $hiredAt->toDateTimeString(),
            'internal_id' => 'EMP9999',
        ])
        ->callMountedTableAction();

    $employee->refresh();

    expect($employee->site_id)->toBe($site->id)
        ->and($employee->project_id)->toBe($project->id)
        ->and($employee->position_id)->toBe($position->id)
        ->and($employee->supervisor_id)->toBe($supervisor->id)
        ->and($employee->hired_at?->format('Y-m-d H:i'))->toBe($hiredAt->format('Y-m-d H:i'));
});
