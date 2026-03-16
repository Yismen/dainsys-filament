<?php

use App\Enums\AbsenceStatuses;
use App\Enums\AbsenceTypes;
use App\Events\EmployeeHiredEvent;
use App\Filament\HumanResource\Resources\Absences\Pages\CreateAbsence;
use App\Filament\HumanResource\Resources\Absences\Pages\EditAbsence;
use App\Filament\HumanResource\Resources\Absences\Pages\ListAbsences;
use App\Filament\HumanResource\Resources\Absences\Pages\ViewAbsence;
use App\Models\Absence;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    Event::fake([EmployeeHiredEvent::class]);

    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $absence = Absence::factory()->for($employee)->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListAbsences::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateAbsence::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditAbsence::getRouteName(),
            'params' => ['record' => $absence->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewAbsence::getRouteName(),
            'params' => ['record' => $absence->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];

    $other_employee = Employee::factory()->create();
    Hire::factory()->for($other_employee)->create();

    $this->form_data = [
        'employee_id' => $other_employee->id,
        'date' => now()->subDay()->format('Y-m-d'),
        'status' => AbsenceStatuses::Created->value,
        'type' => AbsenceTypes::Unjustified->value,
        'comment' => 'Test absence comment',
    ];
});

it('requires users to be authenticated to access Absence resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('requires users to have correct permissions to access Absence resource pages', function (string $method): void {
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

it('allows super admin users to access Absence resource pages', function (string $method): void {
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

it('allows users with correct permissions to access Absence resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Absence'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Absence list page correctly', function (): void {
    $absences = Absence::all();

    actingAs($this->createUserWithPermissionTo('view-any Absence'));

    livewire(ListAbsences::class)
        ->assertCanSeeTableRecords($absences);
});

test('create Absence page works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Absence'));

    livewire(CreateAbsence::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('absences', [
        'employee_id' => $this->form_data['employee_id'],
        'date' => $this->form_data['date'],
    ]);
});

test('edit Absence page works correctly', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $absence = Absence::factory()->for($employee)->create([
        'date' => now()->subDays(2)->format('Y-m-d'),
    ]);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Absence'));

    livewire(EditAbsence::class, ['record' => $absence->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('absences', [
        'id' => $absence->id,
        'employee_id' => $this->form_data['employee_id'],
        'date' => $this->form_data['date'],
    ]);
});

test('form validation requires fields on create page', function (string $field): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Absence'));

    livewire(CreateAbsence::class)
        ->fillForm([$field => ''])
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'employee_id',
    'date',
    'status',
]);

test('create absence form prevents duplicate employee and date combination', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $date = now()->subDay()->format('Y-m-d');

    Absence::factory()->for($employee)->create(['date' => $date]);

    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Absence'));

    livewire(CreateAbsence::class)
        ->fillForm([
            'employee_id' => $employee->id,
            'date' => $date,
            'status' => AbsenceStatuses::Created->value,
        ])
        ->call('create')
        ->assertHasFormErrors(['employee_id']);
});

test('edit absence form allows saving without changing the employee and date combination', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $date = now()->subDay()->format('Y-m-d');
    $absence = Absence::factory()->for($employee)->create(['date' => $date]);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Absence'));

    livewire(EditAbsence::class, ['record' => $absence->getKey()])
        ->fillForm([
            'employee_id' => $employee->id,
            'date' => $date,
            'status' => $absence->status->value,
        ])
        ->call('save')
        ->assertHasNoErrors();
});
