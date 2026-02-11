<?php

use App\Enums\TerminationTypes;
use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Filament\HumanResource\Resources\Terminations\Pages\CreateTermination;
use App\Filament\HumanResource\Resources\Terminations\Pages\EditTermination;
use App\Filament\HumanResource\Resources\Terminations\Pages\ListTerminations;
use App\Filament\HumanResource\Resources\Terminations\Pages\ViewTermination;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Termination;
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
        EmployeeTerminatedEvent::class,
    ]);

    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $termination = Termination::factory()->for($employee)->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListTerminations::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateTermination::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditTermination::getRouteName(),
            'params' => ['record' => $termination->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewTermination::getRouteName(),
            'params' => ['record' => $termination->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];

    $other_employee = Employee::factory()->create();
    Hire::factory()->for($other_employee)->create();

    $this->form_data = [
        'employee_id' => $other_employee->id,
        'termination_type' => TerminationTypes::Resignation,
        'date' => now(),
        'is_rehireable' => true,
        'comment' => 'termination comment',
    ];
});

it('require users to be authenticated to access Termination resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Termination resource pages', function (string $method): void {
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

it('allows super admin users to access Termination resource pages', function (string $method): void {
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

it('allow users with correct permissions to access Termination resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Termination'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Termination list page correctly', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    Termination::factory()->for($employee)->create();
    $terminations = Termination::get();

    actingAs($this->createUserWithPermissionTo('view-any Termination'));

    livewire(ListTerminations::class)
        ->assertCanSeeTableRecords($terminations);
});

test('create Termination page works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Termination'));

    livewire(CreateTermination::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('terminations', $this->form_data);
});

test('edit Termination page works correctly', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $termination = Termination::factory()->for($employee)->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Termination'));

    livewire(EditTermination::class, ['record' => $termination->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('terminations', array_merge(['id' => $termination->id], $this->form_data));
});

test('form validation require fields on create and edit pages', function (string $field): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Termination'));

    // Test CreateTermination validation
    livewire(CreateTermination::class)
        ->fillForm([$field => ''])
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
    // Test EditTermination validation
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $termination = Termination::factory()->for($employee)->create();
    livewire(EditTermination::class, ['record' => $termination->getKey()])
        ->fillForm([$field => ''])
        ->call('save')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'employee_id',
    'date',
    'termination_type',
    'is_rehireable',
    'comment',
]);

// it('autofocus the employee_id field on create and edit pages', function () {
//     actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Termination'));

//     // Test CreateTermination autofocus
//     livewire(CreateTermination::class)
//         ->assertSeeHtml('autofocus');

//     // Test EditTermination autofocus
//     $termination = Termination::factory()->create();
//     livewire(EditTermination::class, ['record' => $termination->getKey()])
//         ->assertSeeHtml('autofocus');
// });
