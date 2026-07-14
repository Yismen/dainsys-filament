<?php

use App\Filament\HumanResource\Resources\Citizenships\Pages\ManageCitizenships;
use App\Models\Citizenship;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    $this->indexRoute = ManageCitizenships::getRouteName();
});

it('requires users to be authenticated to access the Citizenship resource', function (): void {
    $response = get(route($this->indexRoute));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the Citizenship resource', function (): void {
    actingAs(User::factory()->create());

    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the Citizenship resource', function (): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('allows users with correct permissions to access the Citizenship resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Citizenship'));

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('displays Citizenship list page correctly', function (): void {
    $citizenships = Citizenship::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Citizenship'));

    livewire(ManageCitizenships::class)
        ->assertCanSeeTableRecords($citizenships);
});

test('create Citizenship via modal works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Citizenship'));

    livewire(ManageCitizenships::class)
        ->callAction('create', [
            'name' => 'New Citizenship',
        ]);

    $this->assertDatabaseHas('citizenships', [
        'name' => 'New Citizenship',
    ]);
});

test('edit Citizenship via modal works correctly', function (): void {
    $citizenship = Citizenship::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Citizenship'));

    livewire(ManageCitizenships::class)
        ->callAction('edit', $citizenship, [
            'name' => 'Updated Citizenship Name',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('citizenships', [
        'id' => $citizenship->id,
        'name' => 'Updated Citizenship Name',
    ]);
});

test('form validation requires fields on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Citizenship'));

    livewire(ManageCitizenships::class)
        ->callAction('create', [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);

    $citizenship = Citizenship::factory()->create();
    livewire(ManageCitizenships::class)
        ->callAction('edit', $citizenship, [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);
});

test('Citizenship name must be unique on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Citizenship'));

    $existingCitizenship = Citizenship::factory()->create(['name' => 'Unique Citizenship']);

    livewire(ManageCitizenships::class)
        ->callAction('create', [
            'name' => 'Unique Citizenship',
        ])
        ->assertHasFormErrors(['name' => 'unique']);

    $citizenshipToEdit = Citizenship::factory()->create(['name' => 'Another Citizenship']);
    livewire(ManageCitizenships::class)
        ->callAction('edit', $citizenshipToEdit, [
            'name' => 'Unique Citizenship',
        ])
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Citizenship without changing name to trigger uniqueness validation', function (): void {
    $citizenship = Citizenship::factory()->create(['name' => 'Existing Citizenship']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Citizenship'));

    livewire(ManageCitizenships::class)
        ->callAction('edit', $citizenship, [
            'name' => 'Existing Citizenship',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('citizenships', [
        'id' => $citizenship->id,
        'name' => 'Existing Citizenship',
    ]);
});
