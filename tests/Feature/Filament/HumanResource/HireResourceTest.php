<?php

use App\Events\EmployeeHiredEvent;
use App\Filament\HumanResource\Resources\Hires\Pages\CreateHire;
use App\Filament\HumanResource\Resources\Hires\Pages\EditHire;
use App\Filament\HumanResource\Resources\Hires\Pages\ListHires;
use App\Filament\HumanResource\Resources\Hires\Pages\ViewHire;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'), // Where `app` is the ID of the panel you want to test.
    );
    Event::fake([
        EmployeeHiredEvent::class,
    ]);

    $employee = Employee::factory()->create();
    $hire = Hire::factory()->for($employee)->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListHires::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateHire::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditHire::getRouteName(),
            'params' => ['record' => $hire->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewHire::getRouteName(),
            'params' => ['record' => $hire->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];

    $other_employee = Employee::factory()->create();
    // Hire::factory()->for($other_employee)->create();

    $this->form_data = [
        'employee_id' => $other_employee->id,
        'date' => now(),
        'site_id' => Site::factory()->create()->id,
        'project_id' => Project::factory()->create()->id,
        'position_id' => Position::factory()->create()->id,
        'supervisor_id' => Supervisor::factory()->create()->id,
    ];
});

it('require users to be authenticated to access Hire resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Hire resource pages', function (string $method): void {
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

it('allows super admin users to access Hire resource pages', function (string $method): void {
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

it('allow users with correct permissions to access Hire resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Hire'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Hire list page correctly', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $hires = Hire::get();

    actingAs($this->createUserWithPermissionTo('view-any Hire'));

    livewire(ListHires::class)
        ->assertCanSeeTableRecords($hires);
});

test('create Hire page works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Hire'));

    livewire(CreateHire::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('hires', $this->form_data);
});

test('edit Hire page works correctly', function (): void {
    $employee = Employee::factory()->create();
    $hire = Hire::factory()->for($employee)->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Hire'));

    livewire(EditHire::class, ['record' => $hire->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('hires', array_merge(['id' => $hire->id], $this->form_data));
});

test('form validation require fields on create and edit pages', function (string $field): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Hire'));

    // Test CreateHire validation
    livewire(CreateHire::class)
        ->fillForm([$field => ''])
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
    // Test EditHire validation
    $employee = Employee::factory()->create();
    $hire = Hire::factory()->for($employee)->create();
    livewire(EditHire::class, ['record' => $hire->getKey()])
        ->fillForm([$field => ''])
        ->call('save')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'date',
    'employee_id',
    'site_id',
    'project_id',
    'position_id',
    'supervisor_id',
]);

// it('autofocus the employee_id field on create and edit pages', function () {
//     actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Hire'));

//     // Test CreateHire autofocus
//     livewire(CreateHire::class)
//         ->assertSeeHtml('autofocus');

//     // Test EditHire autofocus
//     $hire = Hire::factory()->create();
//     livewire(EditHire::class, ['record' => $hire->getKey()])
//         ->assertSeeHtml('autofocus');
// });
