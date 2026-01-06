<?php

use App\Models\Hire;
use App\Models\User;
use App\Models\Employee;
use App\Models\Suspension;
use App\Models\SuspensionType;
use Filament\Facades\Filament;
use function Pest\Laravel\get;
use App\Events\EmployeeHiredEvent;
use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Event;
use App\Events\SuspensionUpdatedEvent;

use App\Filament\HumanResource\Resources\Suspensions\Pages\EditSuspension;
use App\Filament\HumanResource\Resources\Suspensions\Pages\ViewSuspension;
use App\Filament\HumanResource\Resources\Suspensions\Pages\ListSuspensions;
use App\Filament\HumanResource\Resources\Suspensions\Pages\CreateSuspension;

beforeEach(function () {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'), // Where `app` is the ID of the panel you want to test.
    );
    Event::fake([
        EmployeeHiredEvent::class,
        SuspensionUpdatedEvent::class,
    ]);

    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $suspension = Suspension::factory()->for($employee)->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListSuspensions::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateSuspension::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditSuspension::getRouteName(),
            'params' => ['record' => $suspension->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewSuspension::getRouteName(),
            'params' => ['record' => $suspension->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];

    $other_employee = Employee::factory()->create();
    Hire::factory()->for($other_employee)->create();

    $this->form_data = [
        'employee_id' => $other_employee->id,
        'suspension_type_id' => SuspensionType::factory()->create()->id,
        'starts_at' => now(),
        'ends_at' => now()->addDay(),
        'comment' => 'suspension comment',
    ];
});

it('require users to be authenticated to access Suspension resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Suspension resource pages', function (string $method) {
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

it('allows super admin users to access Suspension resource pages', function (string $method) {
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

it('allow users with correct permissions to access Suspension resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Suspension'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Suspension list page correctly', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    Suspension::factory()->for($employee)->create();
    $suspensions = Suspension::get();

    actingAs($this->createUserWithPermissionTo('view-any Suspension'));

    livewire(ListSuspensions::class)
        ->assertCanSeeTableRecords($suspensions);
});

test('create Suspension page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Suspension'));

    livewire(CreateSuspension::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('suspensions', $this->form_data);
});

test('edit Suspension page works correctly', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $suspension = Suspension::factory()->for($employee)->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Suspension'));

    livewire(EditSuspension::class, ['record' => $suspension->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('suspensions', array_merge(['id' => $suspension->id], $this->form_data));
});

test('form validation require fields on create and edit pages', function (string $field) {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Suspension'));

    // Test CreateSuspension validation
    livewire(CreateSuspension::class)
        ->fillForm([$field => ''])
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
    // Test EditSuspension validation
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $suspension = Suspension::factory()->for($employee)->create();
    livewire(EditSuspension::class, ['record' => $suspension->getKey()])
        ->fillForm([$field => ''])
        ->call('save')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'employee_id',
    'starts_at',
    'ends_at',
    'suspension_type_id',
]);

// it('autofocus the employee_id field on create and edit pages', function () {
//     actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Suspension'));

//     // Test CreateSuspension autofocus
//     livewire(CreateSuspension::class)
//         ->assertSeeHtml('autofocus');

//     // Test EditSuspension autofocus
//     $suspension = Suspension::factory()->create();
//     livewire(EditSuspension::class, ['record' => $suspension->getKey()])
//         ->assertSeeHtml('autofocus');
// });
