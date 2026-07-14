<?php

use App\Filament\HumanResource\Resources\Banks\Pages\ManageBanks;
use App\Models\Bank;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    $this->indexRoute = ManageBanks::getRouteName();

    $this->form_data = [
        'name' => 'Bank Name',
        'person_of_contact' => 'Bank Person',
        'phone' => '8652221155',
        'email' => 'bank@email.com',
        'description' => 'Bank Description',
    ];
});

it('requires users to be authenticated to access the Bank resource', function (): void {
    $response = get(route($this->indexRoute));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the Bank resource', function (): void {
    actingAs(User::factory()->create());

    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the Bank resource', function (): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('allows users with correct permissions to access the Bank resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Bank'));

    $response = get(route($this->indexRoute));

    $response->assertOk();
});

it('displays Bank list page correctly', function (): void {
    $banks = Bank::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'Bank'));

    livewire(ManageBanks::class)
        ->assertCanSeeTableRecords($banks);
});

test('create Bank via modal works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Bank'));

    livewire(ManageBanks::class)
        ->callAction('create', $this->form_data);

    $this->assertDatabaseHas('banks', $this->form_data);
});

test('edit Bank via modal works correctly', function (): void {
    $bank = Bank::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Bank'));

    $update_data = [
        'name' => 'Updated name',
        'person_of_contact' => 'Bank Person',
        'phone' => '9999999999',
        'email' => 'bank@email.com',
    ];

    livewire(ManageBanks::class)
        ->callAction('edit', $bank, $update_data)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('banks', [
        'id' => $bank->id,
        ...$update_data,
    ]);
});

test('form validation requires fields on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Bank'));

    livewire(ManageBanks::class)
        ->callAction('create', [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);

    $bank = Bank::factory()->create();
    livewire(ManageBanks::class)
        ->callAction('edit', $bank, [
            'name' => '',
        ])
        ->assertHasFormErrors(['name' => 'required']);
});

test('Bank name must be unique on create and edit modals', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Bank'));

    $existingBank = Bank::factory()->create(['name' => 'Unique Bank']);

    livewire(ManageBanks::class)
        ->callAction('create', [
            'name' => 'Unique Bank',
        ])
        ->assertHasFormErrors(['name' => 'unique']);

    $bankToEdit = Bank::factory()->create(['name' => 'Another Bank']);
    livewire(ManageBanks::class)
        ->callAction('edit', $bankToEdit, [
            'name' => 'Unique Bank',
        ])
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Bank without changing name to trigger uniqueness validation', function (): void {
    $bank = Bank::factory()->create(['name' => 'Existing Bank']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Bank'));

    livewire(ManageBanks::class)
        ->callAction('edit', $bank, [
            'name' => 'Existing Bank',
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('banks', [
        'id' => $bank->id,
        'name' => 'Existing Bank',
    ]);
});
