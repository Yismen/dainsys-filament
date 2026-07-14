<?php

use App\Events\EmployeeHiredEvent;
use App\Filament\HumanResource\Resources\Hires\Pages\ManageHires;
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
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    $this->indexRoute = ManageHires::getRouteName();

    Event::fake([
        EmployeeHiredEvent::class,
    ]);

    $otherEmployee = Employee::factory()->create();

    $this->form_data = [
        'employee_id' => $otherEmployee->id,
        'date' => now(),
        'site_id' => Site::factory()->create()->id,
        'project_id' => Project::factory()->create()->id,
        'position_id' => Position::factory()->create()->id,
        'supervisor_id' => Supervisor::factory()->create()->id,
    ];
});

it('requires users to be authenticated to access the Hire resource', function (): void {
    $response = get(route($this->indexRoute));
    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the Hire resource', function (): void {
    actingAs(User::factory()->create());
    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the Hire resource', function (): void {
    actingAs($this->createSuperAdminUser());
    $response = get(route($this->indexRoute));
    $response->assertOk();
});

it('allows users with correct permissions to access the Hire resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Hire'));
    $response = get(route($this->indexRoute));
    $response->assertOk();
});

it('displays Hire list page correctly', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $hires = Hire::get();

    actingAs($this->createUserWithPermissionTo('view-any Hire'));

    livewire(ManageHires::class)
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

    livewire(ManageHires::class)
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

    livewire(ManageHires::class)
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

    livewire(ManageHires::class)
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

    livewire(ManageHires::class)
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

    livewire(ManageHires::class)
        ->filterTable('supervisor_id', (string) $supervisorA->id)
        ->assertCanSeeTableRecords([$supervisorAHire])
        ->assertCanNotSeeTableRecords([$supervisorBHire]);
});

test('create Hire via modal works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Hire'));

    livewire(ManageHires::class)
        ->callAction('create', $this->form_data);

    $this->assertDatabaseHas('hires', $this->form_data);
});

test('edit Hire via modal works correctly', function (): void {
    $employee = Employee::factory()->create();
    $hire = Hire::factory()->for($employee)->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Hire'));

    livewire(ManageHires::class)
        ->callAction('edit', $hire, $this->form_data)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('hires', array_merge(['id' => $hire->id], $this->form_data));
});

test('form validation requires fields on create and edit modals', function (string $field): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Hire'));

    livewire(ManageHires::class)
        ->callAction('create', [$field => ''])
        ->assertHasFormErrors([$field => 'required']);

    $employee = Employee::factory()->create();
    $hire = Hire::factory()->for($employee)->create();

    livewire(ManageHires::class)
        ->callAction('edit', $hire, [$field => ''])
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'date',
    'employee_id',
    'site_id',
    'project_id',
    'position_id',
    'supervisor_id',
]);
