<?php

use App\Filament\HumanResource\Resources\Ars\Pages\ManageArs;
use App\Models\Ars;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    $this->indexRoute = ManageArs::getRouteName();

    $this->form_data = [
        'name' => 'Ars name',
        'person_of_contact' => 'Ars person_o_contact',
        'phone' => '5221555666',
        'description' => 'Ars description',
    ];
});

it('requires users to be authenticated to access the Ars resource', function (): void {
    $response = get(route($this->indexRoute));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the Ars resource', function (): void {
    actingAs(User::factory()->create());

    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the Ars resource', function (): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('allows users with correct permissions to access the Ars resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Ars'));

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('displays Ars list page correctly', function (): void {
    $ars = Ars::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Ars'));

    livewire(ManageArs::class)
        ->assertCanSeeTableRecords($ars);
});

test('create Ars via modal works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Ars'));

    livewire(ManageArs::class)
        ->callAction('create', $this->form_data);

    $this->assertDatabaseHas('arss', $this->form_data);
});

test('edit Ars via modal works correctly', function (): void {
    $ars = Ars::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Ars'));

    $update_data = [
        'name' => 'Updated name',
        'person_of_contact' => 'new person',
        'phone' => '5221555666',
    ];

    livewire(ManageArs::class)
        ->callTableAction('edit', $ars, $update_data)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('arss', [
        'id' => $ars->id,
        ...$update_data,
    ]);
});

test('form validation requires fields on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Ars'));

    livewire(ManageArs::class)
        ->callAction('create', [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);

    $ars = Ars::factory()->create();
    livewire(ManageArs::class)
        ->callTableAction('edit', $ars, [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);
});

test('Ars name must be unique on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Ars'));

    $existingArs = Ars::factory()->create(['name' => 'Unique ARS']);

    livewire(ManageArs::class)
        ->callAction('create', [
            'name' => 'Unique ARS',
        ])
        ->assertHasFormErrors(['name' => 'unique']);

    $arsToEdit = Ars::factory()->create(['name' => 'Another ARS']);
    livewire(ManageArs::class)
        ->callTableAction('edit', $arsToEdit, [
            'name' => 'Unique ARS',
        ])
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Ars without changing name to trigger uniqueness validation', function (): void {
    $ars = Ars::factory()->create(['name' => 'Existing ARS']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Ars'));

    livewire(ManageArs::class)
        ->callTableAction('edit', $ars, [
            'name' => 'Existing ARS',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('arss', [
        'id' => $ars->id,
        'name' => 'Existing ARS',
    ]);
});
