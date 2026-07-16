<?php

use App\Filament\HumanResource\Resources\Departments\Pages\ManageDepartments;
use App\Models\Department;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    $this->indexRoute = ManageDepartments::getRouteName();
});

it('requires users to be authenticated to access the Department resource', function (): void {
    $response = get(route($this->indexRoute));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the Department resource', function (): void {
    actingAs(User::factory()->create());

    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the Department resource', function (): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('allows users with correct permissions to access the Department resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Department'));

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('displays Department list page correctly', function (): void {
    $departments = Department::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Department'));

    livewire(ManageDepartments::class)
        ->assertCanSeeTableRecords($departments);
});

test('create Department via modal works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Department'));

    livewire(ManageDepartments::class)
        ->callAction('create', [
            'name' => 'New Department',
        ]);

    $this->assertDatabaseHas('departments', [
        'name' => 'New Department',
    ]);
});

test('edit Department via modal works correctly', function (): void {
    $department = Department::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Department'));

    livewire(ManageDepartments::class)
        ->callTableAction('edit', $department, [
            'name' => 'Updated Department Name',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('departments', [
        'id' => $department->id,
        'name' => 'Updated Department Name',
    ]);
});

test('form validation requires fields on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Department'));

    livewire(ManageDepartments::class)
        ->callAction('create', [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);

    $department = Department::factory()->create();
    livewire(ManageDepartments::class)
        ->callTableAction('edit', $department, [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);
});

test('Department name must be unique on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Department'));

    $existingDepartment = Department::factory()->create(['name' => 'Unique Department']);

    livewire(ManageDepartments::class)
        ->callAction('create', [
            'name' => 'Unique Department',
        ])
        ->assertHasFormErrors(['name' => 'unique']);

    $departmentToEdit = Department::factory()->create(['name' => 'Another Department']);
    livewire(ManageDepartments::class)
        ->callTableAction('edit', $departmentToEdit, [
            'name' => 'Unique Department',
        ])
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Department without changing name to trigger uniqueness validation', function (): void {
    $department = Department::factory()->create(['name' => 'Existing Department']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Department'));

    livewire(ManageDepartments::class)
        ->callTableAction('edit', $department, [
            'name' => 'Existing Department',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('departments', [
        'id' => $department->id,
        'name' => 'Existing Department',
    ]);
});
