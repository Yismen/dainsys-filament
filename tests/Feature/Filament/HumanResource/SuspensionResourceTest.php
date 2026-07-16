<?php

use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeSuspendedEvent;
use App\Filament\HumanResource\Resources\Suspensions\Pages\ManageSuspensions;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Suspension;
use App\Models\SuspensionType;
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

    $this->indexRoute = ManageSuspensions::getRouteName();

    Event::fake([
        EmployeeHiredEvent::class,
        EmployeeSuspendedEvent::class,
    ]);

    $otherEmployee = Employee::factory()->create();
    Hire::factory()->for($otherEmployee)->create();

    $this->form_data = [
        'employee_id' => $otherEmployee->id,
        'suspension_type_id' => SuspensionType::factory()->create()->id,
        'starts_at' => now(),
        'ends_at' => now()->addDay(),
        'comment' => 'suspension comment',
    ];
});

it('requires users to be authenticated to access the Suspension resource', function (): void {
    $response = get(route($this->indexRoute));
    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the Suspension resource', function (): void {
    actingAs(User::factory()->create());
    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the Suspension resource', function (): void {
    actingAs($this->createSuperAdminUser());
    $response = get(route($this->indexRoute));
    $response->assertOk();
});

it('allows users with correct permissions to access the Suspension resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Suspension'));
    $response = get(route($this->indexRoute));
    $response->assertOk();
});

it('displays Suspension list page correctly', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    Suspension::factory()->for($employee)->create();
    $suspensions = Suspension::get();

    actingAs($this->createUserWithPermissionTo('view-any Suspension'));

    livewire(ManageSuspensions::class)
        ->assertCanSeeTableRecords($suspensions);
});

test('can filter Suspensions by starts_at date range', function (): void {
    $oldEmployee = Employee::factory()->create();
    Hire::factory()->for($oldEmployee)->state(['date' => now()->subMonths(2)->toDateString()])->create();

    $recentEmployee = Employee::factory()->create();
    Hire::factory()->for($recentEmployee)->state(['date' => now()->subWeek()->toDateString()])->create();

    $oldSuspension = Suspension::factory()
        ->for($oldEmployee)
        ->state([
            'starts_at' => now()->subMonth()->toDateString(),
            'ends_at' => now()->subMonth()->addDay()->toDateString(),
        ])
        ->create();

    $recentSuspension = Suspension::factory()
        ->for($recentEmployee)
        ->state([
            'starts_at' => now()->toDateString(),
            'ends_at' => now()->addDay()->toDateString(),
        ])
        ->create();

    actingAs($this->createUserWithPermissionTo('view-any Suspension'));

    livewire(ManageSuspensions::class)
        ->filterTable('starts_at', [
            'starts_at_from' => now()->subWeek()->toDateString(),
            'starts_at_until' => now()->toDateString(),
        ])
        ->assertCanSeeTableRecords([$recentSuspension])
        ->assertCanNotSeeTableRecords([$oldSuspension]);
});

test('can filter Suspensions by suspension type', function (): void {
    $firstType = SuspensionType::factory()->create();
    $secondType = SuspensionType::factory()->create();

    $firstTypeEmployee = Employee::factory()->create();
    Hire::factory()->for($firstTypeEmployee)->state(['date' => now()->subWeek()->toDateString()])->create();

    $secondTypeEmployee = Employee::factory()->create();
    Hire::factory()->for($secondTypeEmployee)->state(['date' => now()->subWeek()->toDateString()])->create();

    $firstTypeSuspension = Suspension::factory()
        ->for($firstTypeEmployee)
        ->for($firstType)
        ->create();

    $secondTypeSuspension = Suspension::factory()
        ->for($secondTypeEmployee)
        ->for($secondType)
        ->create();

    actingAs($this->createUserWithPermissionTo('view-any Suspension'));

    livewire(ManageSuspensions::class)
        ->filterTable('suspension_type_id', (string) $firstType->id)
        ->assertCanSeeTableRecords([$firstTypeSuspension])
        ->assertCanNotSeeTableRecords([$secondTypeSuspension]);
});

test('can filter Suspensions by status', function (): void {
    $pendingEmployee = Employee::factory()->create();
    Hire::factory()->for($pendingEmployee)->state(['date' => now()->subWeek()->toDateString()])->create();

    $completedEmployee = Employee::factory()->create();
    Hire::factory()->for($completedEmployee)->state(['date' => now()->subWeek()->toDateString()])->create();

    $pendingSuspension = Suspension::factory()
        ->for($pendingEmployee)
        ->state([
            'starts_at' => now()->addDay()->toDateString(),
            'ends_at' => now()->addDays(2)->toDateString(),
        ])
        ->create();

    $completedSuspension = Suspension::factory()
        ->for($completedEmployee)
        ->state([
            'starts_at' => now()->subDays(3)->toDateString(),
            'ends_at' => now()->subDay()->toDateString(),
        ])
        ->create();

    actingAs($this->createUserWithPermissionTo('view-any Suspension'));

    livewire(ManageSuspensions::class)
        ->filterTable('status', 'Pending')
        ->assertCanSeeTableRecords([$pendingSuspension])
        ->assertCanNotSeeTableRecords([$completedSuspension]);
});

test('create Suspension via modal works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Suspension'));

    livewire(ManageSuspensions::class)
        ->callAction('create', $this->form_data);

    $this->assertDatabaseHas('suspensions', $this->form_data);
});

test('edit Suspension via modal works correctly', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $suspension = Suspension::factory()->for($employee)->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Suspension'));

    livewire(ManageSuspensions::class)
        ->callTableAction('edit', $suspension, $this->form_data)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('suspensions', array_merge(['id' => $suspension->id], $this->form_data));
});

test('form validation requires fields on create and edit modals', function (string $field): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Suspension'));

    livewire(ManageSuspensions::class)
        ->callAction('create', [$field => ''])
        ->assertHasFormErrors([$field => 'required']);

    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $suspension = Suspension::factory()->for($employee)->create();

    livewire(ManageSuspensions::class)
        ->callTableAction('edit', $suspension, [$field => ''])
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'employee_id',
    'starts_at',
    'ends_at',
    'suspension_type_id',
]);
