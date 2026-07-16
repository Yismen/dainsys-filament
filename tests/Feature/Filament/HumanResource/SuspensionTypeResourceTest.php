<?php

use App\Filament\HumanResource\Resources\SuspensionTypes\Pages\ManageSuspensionTypes;
use App\Models\SuspensionType;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    $this->indexRoute = ManageSuspensionTypes::getRouteName();
});

it('requires users to be authenticated to access the SuspensionType resource', function (): void {
    $response = get(route($this->indexRoute));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the SuspensionType resource', function (): void {
    actingAs(User::factory()->create());

    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the SuspensionType resource', function (): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('allows users with correct permissions to access the SuspensionType resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'SuspensionType'));

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('displays SuspensionType list page correctly', function (): void {
    $suspension_types = SuspensionType::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'SuspensionType'));

    livewire(ManageSuspensionTypes::class)
        ->assertCanSeeTableRecords($suspension_types);
});

test('create SuspensionType via modal works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'SuspensionType'));

    livewire(ManageSuspensionTypes::class)
        ->callAction('create', [
            'name' => 'New SuspensionType',
        ]);

    $this->assertDatabaseHas('suspension_types', [
        'name' => 'New SuspensionType',
    ]);
});

test('edit SuspensionType via modal works correctly', function (): void {
    $suspension_type = SuspensionType::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'SuspensionType'));

    livewire(ManageSuspensionTypes::class)
        ->callTableAction('edit', $suspension_type, [
            'name' => 'Updated SuspensionType Name',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('suspension_types', [
        'id' => $suspension_type->id,
        'name' => 'Updated SuspensionType Name',
    ]);
});

test('form validation requires fields on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'SuspensionType'));

    livewire(ManageSuspensionTypes::class)
        ->callAction('create', [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);

    $suspension_type = SuspensionType::factory()->create();
    livewire(ManageSuspensionTypes::class)
        ->callTableAction('edit', $suspension_type, [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);
});

test('SuspensionType name must be unique on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'SuspensionType'));

    $existingSuspensionType = SuspensionType::factory()->create(['name' => 'Unique SuspensionType']);

    livewire(ManageSuspensionTypes::class)
        ->callAction('create', [
            'name' => 'Unique SuspensionType',
        ])
        ->assertHasFormErrors(['name' => 'unique']);

    $suspension_typeToEdit = SuspensionType::factory()->create(['name' => 'Another SuspensionType']);
    livewire(ManageSuspensionTypes::class)
        ->callTableAction('edit', $suspension_typeToEdit, [
            'name' => 'Unique SuspensionType',
        ])
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating SuspensionType without changing name to trigger uniqueness validation', function (): void {
    $suspension_type = SuspensionType::factory()->create(['name' => 'Existing SuspensionType']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'SuspensionType'));

    livewire(ManageSuspensionTypes::class)
        ->callTableAction('edit', $suspension_type, [
            'name' => 'Existing SuspensionType',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('suspension_types', [
        'id' => $suspension_type->id,
        'name' => 'Existing SuspensionType',
    ]);
});
