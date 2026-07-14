<?php

use App\Enums\TerminationTypes;
use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Filament\HumanResource\Resources\Terminations\Pages\ManageTerminations;
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
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    $this->indexRoute = ManageTerminations::getRouteName();

    Event::fake([
        EmployeeHiredEvent::class,
        EmployeeTerminatedEvent::class,
    ]);

    $otherEmployee = Employee::factory()->create();
    Hire::factory()->for($otherEmployee)->create();

    $this->form_data = [
        'employee_id' => $otherEmployee->id,
        'termination_type' => TerminationTypes::Resignation,
        'date' => now(),
        'is_rehireable' => true,
        'comment' => 'termination comment',
    ];
});

it('requires users to be authenticated to access the Termination resource', function (): void {
    $response = get(route($this->indexRoute));
    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the Termination resource', function (): void {
    actingAs(User::factory()->create());
    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the Termination resource', function (): void {
    actingAs($this->createSuperAdminUser());
    $response = get(route($this->indexRoute));
    $response->assertOk();
});

it('allows users with correct permissions to access the Termination resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Termination'));
    $response = get(route($this->indexRoute));
    $response->assertOk();
});

it('displays Termination list page correctly', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    Termination::factory()->for($employee)->create();
    $terminations = Termination::get();

    actingAs($this->createUserWithPermissionTo('view-any Termination'));

    livewire(ManageTerminations::class)
        ->assertCanSeeTableRecords($terminations);
});

test('can filter Terminations by rehireable status', function (): void {
    $rehireableEmployee = Employee::factory()->create();
    Hire::factory()->for($rehireableEmployee)->state(['date' => now()->subWeek()->toDateString()])->create();

    $notRehireableEmployee = Employee::factory()->create();
    Hire::factory()->for($notRehireableEmployee)->state(['date' => now()->subWeek()->toDateString()])->create();

    $rehireableTermination = Termination::factory()
        ->for($rehireableEmployee)
        ->state(['is_rehireable' => true])
        ->create();

    $notRehireableTermination = Termination::factory()
        ->for($notRehireableEmployee)
        ->state(['is_rehireable' => false])
        ->create();

    actingAs($this->createUserWithPermissionTo('view-any Termination'));

    livewire(ManageTerminations::class)
        ->filterTable('is_rehireable', '1')
        ->assertCanSeeTableRecords([$rehireableTermination])
        ->assertCanNotSeeTableRecords([$notRehireableTermination]);
});

test('can filter Terminations by date range', function (): void {
    $oldTerminationEmployee = Employee::factory()->create();
    Hire::factory()->for($oldTerminationEmployee)->state(['date' => now()->subMonths(2)->toDateString()])->create();

    $recentTerminationEmployee = Employee::factory()->create();
    Hire::factory()->for($recentTerminationEmployee)->state(['date' => now()->subWeek()->toDateString()])->create();

    $oldTermination = Termination::factory()
        ->for($oldTerminationEmployee)
        ->state(['date' => now()->subMonth()->toDateString()])
        ->create();

    $recentTermination = Termination::factory()
        ->for($recentTerminationEmployee)
        ->state(['date' => now()->toDateString()])
        ->create();

    actingAs($this->createUserWithPermissionTo('view-any Termination'));

    livewire(ManageTerminations::class)
        ->filterTable('date', [
            'date_from' => now()->subWeek()->toDateString(),
            'date_until' => now()->toDateString(),
        ])
        ->assertCanSeeTableRecords([$recentTermination])
        ->assertCanNotSeeTableRecords([$oldTermination]);
});

test('create Termination via modal works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Termination'));

    livewire(ManageTerminations::class)
        ->callAction('create', $this->form_data);

    $this->assertDatabaseHas('terminations', $this->form_data);
});

test('edit Termination via modal works correctly', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $termination = Termination::factory()->for($employee)->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Termination'));

    livewire(ManageTerminations::class)
        ->callAction('edit', $termination, $this->form_data)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('terminations', array_merge(['id' => $termination->id], $this->form_data));
});

test('form validation requires fields on create and edit modals', function (string $field): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Termination'));

    livewire(ManageTerminations::class)
        ->callAction('create', [$field => ''])
        ->assertHasFormErrors([$field => 'required']);

    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $termination = Termination::factory()->for($employee)->create();

    livewire(ManageTerminations::class)
        ->callAction('edit', $termination, [$field => ''])
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'employee_id',
    'date',
    'termination_type',
    'is_rehireable',
    'comment',
]);
