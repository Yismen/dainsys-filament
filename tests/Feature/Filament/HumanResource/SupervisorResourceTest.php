<?php

use App\Filament\HumanResource\Resources\Supervisors\Pages\ManageSupervisors;
use App\Models\Supervisor;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    $this->indexRoute = ManageSupervisors::getRouteName();
});

it('requires users to be authenticated to access the Supervisor resource', function (): void {
    $response = get(route($this->indexRoute));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the Supervisor resource', function (): void {
    actingAs(User::factory()->create());

    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the Supervisor resource', function (): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('allows users with correct permissions to access the Supervisor resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Supervisor'));

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('displays Supervisor list page correctly', function (): void {
    $supervisors = Supervisor::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Supervisor'));

    livewire(ManageSupervisors::class)
        ->assertCanSeeTableRecords($supervisors);
});

test('create Supervisor via modal works correctly', function (): void {
    $user = User::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Supervisor'));

    livewire(ManageSupervisors::class)
        ->callAction('create', [
            'user_id' => $user->id,
            'name' => 'New Supervisor',
        ]);

    $this->assertDatabaseHas('supervisors', [
        'name' => 'New Supervisor',
        'user_id' => $user->id,
    ]);
});

test('edit Supervisor via modal works correctly', function (): void {
    $supervisor = Supervisor::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Supervisor'));

    livewire(ManageSupervisors::class)
        ->callAction('edit', $supervisor, [
            'user_id' => $supervisor->user_id,
            'name' => 'Updated Supervisor Name',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('supervisors', [
        'id' => $supervisor->id,
        'name' => 'Updated Supervisor Name',
        'user_id' => $supervisor->user_id,
    ]);
});

test('form validation requires fields on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Supervisor'));

    livewire(ManageSupervisors::class)
        ->callAction('create', [
            'user_id' => null,
            'name' => '',
        ])
        ->assertHasFormErrors([
            'user_id' => 'required',
            'name' => 'required',
        ]);

    $supervisor = Supervisor::factory()->create();
    livewire(ManageSupervisors::class)
        ->callAction('edit', $supervisor, [
            'user_id' => null,
            'name' => '',
        ])
        ->assertHasFormErrors([
            'user_id' => 'required',
            'name' => 'required',
        ]);
});

test('Supervisor name must be unique on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Supervisor'));

    $existingSupervisor = Supervisor::factory()->create(['name' => 'Unique Supervisor']);

    livewire(ManageSupervisors::class)
        ->callAction('create', [
            'user_id' => User::factory()->create()->id,
            'name' => 'Unique Supervisor',
        ])
        ->assertHasFormErrors(['name' => 'unique']);

    $supervisorToEdit = Supervisor::factory()->create(['name' => 'Another Supervisor']);
    livewire(ManageSupervisors::class)
        ->callAction('edit', $supervisorToEdit, [
            'user_id' => $supervisorToEdit->user_id,
            'name' => 'Unique Supervisor',
        ])
        ->assertHasFormErrors(['name' => 'unique']);
});

test('Supervisor user must be unique on create', function (): void {
    $user = User::factory()->create();
    Supervisor::factory()->create(['user_id' => $user->id]);

    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Supervisor'));

    livewire(ManageSupervisors::class)
        ->callAction('create', [
            'user_id' => $user->id,
            'name' => 'Second Supervisor',
        ])
        ->assertHasFormErrors([
            'user_id' => 'unique',
        ]);
});

it('allows updating Supervisor without changing name to trigger uniqueness validation', function (): void {
    $supervisor = Supervisor::factory()->create(['name' => 'Existing Supervisor']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Supervisor'));

    livewire(ManageSupervisors::class)
        ->callAction('edit', $supervisor, [
            'user_id' => $supervisor->user_id,
            'name' => 'Existing Supervisor',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('supervisors', [
        'id' => $supervisor->id,
        'name' => 'Existing Supervisor',
        'user_id' => $supervisor->user_id,
    ]);
});
