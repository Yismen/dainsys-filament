<?php

use App\Filament\HumanResource\Resources\Sites\Pages\ManageSites;
use App\Models\Site;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    $this->indexRoute = ManageSites::getRouteName();
});

it('requires users to be authenticated to access the Site resource', function (): void {
    $response = get(route($this->indexRoute));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the Site resource', function (): void {
    actingAs(User::factory()->create());

    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the Site resource', function (): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('allows users with correct permissions to access the Site resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Site'));

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('displays Site list page correctly', function (): void {
    $sites = Site::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Site'));

    livewire(ManageSites::class)
        ->assertCanSeeTableRecords($sites);
});

test('create Site via modal works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Site'));

    livewire(ManageSites::class)
        ->callAction('create', [
            'name' => 'new name',
            'person_of_contact' => 'new person_of_contact',
            'phone' => '8456665555',
            'email' => 'email@mail.com',
            'address' => 'new address',
            'geolocation' => 'new geolocation',
            'description' => 'new description',
        ]);

    $this->assertDatabaseHas('sites', [
        'name' => 'new name',
    ]);
});

test('edit Site via modal works correctly', function (): void {
    $site = Site::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Site'));

    livewire(ManageSites::class)
        ->callTableAction('edit', $site, [
            'name' => 'Updated Site Name',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('sites', [
        'id' => $site->id,
        'name' => 'Updated Site Name',
    ]);
});

test('form validation requires fields on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Site'));

    livewire(ManageSites::class)
        ->callAction('create', [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);

    $site = Site::factory()->create();
    livewire(ManageSites::class)
        ->callTableAction('edit', $site, [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);
});

test('Site name must be unique on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Site'));

    $existingSite = Site::factory()->create(['name' => 'Unique Site']);

    livewire(ManageSites::class)
        ->callAction('create', [
            'name' => 'Unique Site',
        ])
        ->assertHasFormErrors(['name' => 'unique']);

    $siteToEdit = Site::factory()->create(['name' => 'Another Site']);
    livewire(ManageSites::class)
        ->callTableAction('edit', $siteToEdit, [
            'name' => 'Unique Site',
        ])
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Site without changing name to trigger uniqueness validation', function (): void {
    $site = Site::factory()->create(['name' => 'Existing Site']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Site'));

    livewire(ManageSites::class)
        ->callTableAction('edit', $site, [
            'name' => 'Existing Site',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('sites', [
        'id' => $site->id,
        'name' => 'Existing Site',
    ]);
});
