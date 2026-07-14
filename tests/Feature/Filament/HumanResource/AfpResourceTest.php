<?php

use App\Filament\HumanResource\Resources\Afps\Pages\ManageAfps;
use App\Models\Afp;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    $this->indexRoute = ManageAfps::getRouteName();

    $this->form_data = [
        'name' => 'New Name',
        'person_of_contact' => 'Person of Contact',
        'phone' => '8625543345',
        'description' => 'this is the afp',
    ];
});

it('requires users to be authenticated to access the Afp resource', function (): void {
    $response = get(route($this->indexRoute));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the Afp resource', function (): void {
    actingAs(User::factory()->create());

    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the Afp resource', function (): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('allows users with correct permissions to access the Afp resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Afp'));

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('displays Afp list page correctly', function (): void {
    $afps = Afp::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Afp'));

    livewire(ManageAfps::class)
        ->assertCanSeeTableRecords($afps);
});

test('create Afp via modal works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Afp'));

    livewire(ManageAfps::class)
        ->callAction('create', $this->form_data);

    $this->assertDatabaseHas('afps', $this->form_data);
});

test('edit Afp via modal works correctly', function (): void {
    $afp = Afp::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Afp'));

    $update_data = [
        'name' => 'Updated name',
        'person_of_contact' => 'new person',
        'phone' => '6245887755',
    ];

    livewire(ManageAfps::class)
        ->callAction('edit', $afp, $update_data)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('afps', [
        'id' => $afp->id,
        ...$update_data,
    ]);
});

test('form validation requires fields on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Afp'));

    livewire(ManageAfps::class)
        ->callAction('create', [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);

    $afp = Afp::factory()->create();
    livewire(ManageAfps::class)
        ->callAction('edit', $afp, [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);
});

test('Afp name must be unique on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Afp'));

    $existingAfp = Afp::factory()->create(['name' => 'Unique AFP']);

    livewire(ManageAfps::class)
        ->callAction('create', [
            'name' => 'Unique AFP',
        ])
        ->assertHasFormErrors(['name' => 'unique']);

    $afpToEdit = Afp::factory()->create(['name' => 'Another AFP']);
    livewire(ManageAfps::class)
        ->callAction('edit', $afpToEdit, [
            'name' => 'Unique AFP',
        ])
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Afp without changing name to trigger uniqueness validation', function (): void {
    $afp = Afp::factory()->create(['name' => 'Existing AFP']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Afp'));

    livewire(ManageAfps::class)
        ->callAction('edit', $afp, [
            'name' => 'Existing AFP',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('afps', [
        'id' => $afp->id,
        'name' => 'Existing AFP',
    ]);
});
