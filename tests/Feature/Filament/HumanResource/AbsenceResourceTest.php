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
]);

it('requires users to have correct permissions to access Absence resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertForbidden();
})->with([
    'index',
]);

it('allows super admin users to access Absence resource pages', function (string $method): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
]);

it('allows users with correct permissions to access Absence resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Absence'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
]);

it('displays Absence list page correctly', function (): void {
    $absences = Absence::all();

    actingAs($this->createUserWithPermissionTo('view-any Absence'));

    livewire(ListAbsences::class)
        ->assertCanSeeTableRecords($absences);
});


