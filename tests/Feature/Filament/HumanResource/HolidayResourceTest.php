<?php

use App\Filament\HumanResource\Resources\Holidays\Pages\ManageHolidays;
use App\Models\Holiday;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    $this->indexRoute = ManageHolidays::getRouteName();
});

it('requires users to be authenticated to access the Holiday resource', function (): void {
    $response = get(route($this->indexRoute));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the Holiday resource', function (): void {
    actingAs(User::factory()->create());

    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the Holiday resource', function (): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('allows users with correct permissions to access the Holiday resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Holiday'));

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('displays Holiday list page correctly', function (): void {
    $holidays = Holiday::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Holiday'));

    livewire(ManageHolidays::class)
        ->assertCanSeeTableRecords($holidays);
});

test('create Holiday via modal works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Holiday'));

    livewire(ManageHolidays::class)
        ->callAction('create', [
            'name' => 'New Holiday',
            'date' => now()->addDays(10)->format('Y-m-d'),
            'description' => 'Holiday description',
        ]);

    $this->assertDatabaseHas('holidays', [
        'name' => 'New Holiday',
    ]);
});

test('edit Holiday via modal works correctly', function (): void {
    $holiday = Holiday::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Holiday'));

    livewire(ManageHolidays::class)
        ->callTableAction('edit', $holiday, [
            'name' => 'Updated Holiday Name',
            'date' => $holiday->date,
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('holidays', [
        'id' => $holiday->id,
        'name' => 'Updated Holiday Name',
    ]);
});

test('form validation requires fields on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Holiday'));

    livewire(ManageHolidays::class)
        ->callAction('create', [
            'name' => '',
            'date' => '',
        ])
        ->assertHasFormErrors(['name' => 'required', 'date' => 'required']);

    $holiday = Holiday::factory()->create();
    livewire(ManageHolidays::class)
        ->callTableAction('edit', $holiday, [
            'name' => '',
            'date' => '',
        ])
        ->assertHasFormErrors(['name' => 'required', 'date' => 'required']);
});

test('Holiday date must be unique on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Holiday'));

    $existingHoliday = Holiday::factory()->create(['date' => now()->format('Y-m-d')]);

    livewire(ManageHolidays::class)
        ->callAction('create', [
            'name' => 'Unique Holiday',
            'date' => now()->format('Y-m-d'),
        ])
        ->assertHasFormErrors(['date' => 'unique']);

    $holidayToEdit = Holiday::factory()->create();
    livewire(ManageHolidays::class)
        ->callTableAction('edit', $holidayToEdit, [
            'date' => now()->format('Y-m-d'),
        ])
        ->assertHasFormErrors(['date' => 'unique']);
});

it('allows updating Holiday without changing date to trigger uniqueness validation', function (): void {
    $holiday = Holiday::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Holiday'));

    livewire(ManageHolidays::class)
        ->callTableAction('edit', $holiday, [
            'date' => $holiday->date,
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('holidays', [
        'id' => $holiday->id,
        'date' => $holiday->date,
    ]);
});
