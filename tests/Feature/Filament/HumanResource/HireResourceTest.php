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

test('can filter Hires by date range', function (): void {
    $oldEmployee = Employee::factory()->create();
    $recentEmployee = Employee::factory()->create();

    $oldHire = Hire::factory()
        ->for($oldEmployee)
        ->state(['date' => now()->subMonth()->toDateString()])
        ->create();

    $recentHire = Hire::factory()
        ->for($recentEmployee)
        ->state(['date' => now()->toDateString()])
        ->create();

    actingAs($this->createUserWithPermissionTo('view-any Hire'));

    livewire(ListHires::class)
        ->filterTable('date', [
            'date_from' => now()->subWeek()->toDateString(),
            'date_until' => now()->toDateString(),
        ])
        ->assertCanSeeTableRecords([$recentHire])
        ->assertCanNotSeeTableRecords([$oldHire]);
});

test('can filter Hires by site', function (): void {
    $siteA = Site::factory()->create();
    $siteB = Site::factory()->create();

    $siteAEmployee = Employee::factory()->create();
    $siteBEmployee = Employee::factory()->create();

    $siteAHire = Hire::factory()->for($siteAEmployee)->for($siteA)->create();
    $siteBHire = Hire::factory()->for($siteBEmployee)->for($siteB)->create();

    actingAs($this->createUserWithPermissionTo('view-any Hire'));

    livewire(ListHires::class)
        ->filterTable('site_id', (string) $siteA->id)
        ->assertCanSeeTableRecords([$siteAHire])
        ->assertCanNotSeeTableRecords([$siteBHire]);
});

test('can filter Hires by project', function (): void {
    $projectA = Project::factory()->create();
    $projectB = Project::factory()->create();

    $projectAEmployee = Employee::factory()->create();
    $projectBEmployee = Employee::factory()->create();

    $projectAHire = Hire::factory()->for($projectAEmployee)->for($projectA)->create();
    $projectBHire = Hire::factory()->for($projectBEmployee)->for($projectB)->create();

    actingAs($this->createUserWithPermissionTo('view-any Hire'));

    livewire(ListHires::class)
        ->filterTable('project_id', (string) $projectA->id)
        ->assertCanSeeTableRecords([$projectAHire])
        ->assertCanNotSeeTableRecords([$projectBHire]);
});

test('can filter Hires by position', function (): void {
    $positionA = Position::factory()->create();
    $positionB = Position::factory()->create();

    $positionAEmployee = Employee::factory()->create();
    $positionBEmployee = Employee::factory()->create();

    $positionAHire = Hire::factory()->for($positionAEmployee)->for($positionA)->create();
    $positionBHire = Hire::factory()->for($positionBEmployee)->for($positionB)->create();

    actingAs($this->createUserWithPermissionTo('view-any Hire'));

    livewire(ListHires::class)
        ->filterTable('position_id', (string) $positionA->id)
        ->assertCanSeeTableRecords([$positionAHire])
        ->assertCanNotSeeTableRecords([$positionBHire]);
});

test('can filter Hires by supervisor', function (): void {
    $supervisorA = Supervisor::factory()->create();
    $supervisorB = Supervisor::factory()->create();

    $supervisorAEmployee = Employee::factory()->create();
    $supervisorBEmployee = Employee::factory()->create();

    $supervisorAHire = Hire::factory()->for($supervisorAEmployee)->for($supervisorA)->create();
    $supervisorBHire = Hire::factory()->for($supervisorBEmployee)->for($supervisorB)->create();

    actingAs($this->createUserWithPermissionTo('view-any Hire'));

    livewire(ListHires::class)
        ->filterTable('supervisor_id', (string) $supervisorA->id)
        ->assertCanSeeTableRecords([$supervisorAHire])
        ->assertCanNotSeeTableRecords([$supervisorBHire]);
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
