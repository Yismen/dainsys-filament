<?php

use App\Enums\SalaryTypes;
use App\Filament\HumanResource\Resources\Positions\Pages\ManagePositions;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    $this->indexRoute = ManagePositions::getRouteName();
});

it('requires users to be authenticated to access the Position resource', function (): void {
    $response = get(route($this->indexRoute));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the Position resource', function (): void {
    actingAs(User::factory()->create());

    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the Position resource', function (): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('allows users with correct permissions to access the Position resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Position'));

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('displays Position list page correctly', function (): void {
    $positions = Position::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Position'));

    livewire(ManagePositions::class)
        ->assertCanSeeTableRecords($positions);
});

test('create Position via modal works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Position'));

    $name = 'new Position';
    livewire(ManagePositions::class)
        ->callAction('create', [
            'name' => $name,
            'department_id' => Department::factory()->create()->id,
            'salary_type' => SalaryTypes::Salary->value,
            'salary' => 50000,
        ]);

    $this->assertDatabaseHas('positions', [
        'name' => $name,
    ]);
});

test('edit Position via modal works correctly', function (): void {
    $position = Position::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Position'));

    $newName = 'Updated Position Name';
    livewire(ManagePositions::class)
        ->callAction('edit', $position, [
            'name' => $newName,
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('positions', [
        'id' => $position->id,
        'name' => $newName,
    ]);
});

test('form validation requires fields on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Position'));

    livewire(ManagePositions::class)
        ->callAction('create', [
            'name' => '',
            'department_id' => '',
            'salary_type' => '',
            'salary' => '',
        ])
        ->assertHasFormErrors([
            'name' => 'required',
            'department_id' => 'required',
            'salary_type' => 'required',
            'salary' => 'required',
        ]);

    $position = Position::factory()->create();
    livewire(ManagePositions::class)
        ->callAction('edit', $position, [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);
});

test('Position name must be unique on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Position'));

    $existingPosition = Position::factory()->create(['name' => 'Unique Position']);

    livewire(ManagePositions::class)
        ->callAction('create', [
            'name' => 'Unique Position',
        ])
        ->assertHasFormErrors(['name' => 'unique']);

    $positionToEdit = Position::factory()->create(['name' => 'Another Position']);
    livewire(ManagePositions::class)
        ->callAction('edit', $positionToEdit, [
            'name' => 'Unique Position',
        ])
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Position without changing name to trigger uniqueness validation', function (): void {
    $position = Position::factory()->create(['name' => 'Existing Position']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Position'));

    livewire(ManagePositions::class)
        ->callAction('edit', $position, [
            'name' => 'Existing Position',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('positions', [
        'id' => $position->id,
        'name' => 'Existing Position',
    ]);
});
