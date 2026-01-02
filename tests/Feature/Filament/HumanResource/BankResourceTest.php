<?php

use App\Filament\HumanResource\Resources\Banks\Pages\CreateBank;
use App\Filament\HumanResource\Resources\Banks\Pages\EditBank;
use App\Filament\HumanResource\Resources\Banks\Pages\ListBanks;
use App\Filament\HumanResource\Resources\Banks\Pages\ViewBank;
use App\Models\Bank;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'), // Where `app` is the ID of the panel you want to test.
    );
    $bank = Bank::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListBanks::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateBank::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditBank::getRouteName(),
            'params' => ['record' => $bank->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewBank::getRouteName(),
            'params' => ['record' => $bank->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];

    $this->form_data = [
        'name' => 'Bank Name',
        'person_of_contact' => 'Bank Person',
        'phone' => '8652221155',
        'email' => 'bank@email.com',
        'description' => 'Bank Description',
    ];
});

it('require users to be authenticated to access Bank resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Bank resource pages', function (string $method) {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));
    $response->assertForbidden();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('allows super admin users to access Bank resource pages', function (string $method) {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('allow users with correct permissions to access Bank resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Bank'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Bank list page correctly', function () {
    $banks = Bank::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Bank'));

    livewire(ListBanks::class)
        ->assertCanSeeTableRecords($banks);
});

test('create Bank page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Bank'));

    livewire(CreateBank::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('banks', $this->form_data);
});

test('edit Bank page works correctly', function () {
    $bank = Bank::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Bank'));

    $this->form_data['id'] = $bank->id;
    $this->form_data['name'] = 'Updated name';
    $this->form_data['phone'] = '9999999999';
    livewire(EditBank::class, ['record' => $bank->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('banks', $this->form_data);
});

test('form validation require fields on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Bank'));

    // Test CreateBank validation
    livewire(CreateBank::class)
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
    // Test EditBank validation
    $bank = Bank::factory()->create();
    livewire(EditBank::class, ['record' => $bank->getKey()])
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

test('Bank name must be unique on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Bank'));

    $existingBank = Bank::factory()->create(['name' => 'Unique Bank']);

    // Test CreateBank uniqueness validation
    livewire(CreateBank::class)
        ->fillForm([
            'name' => 'Unique Bank', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
    // Test EditBank uniqueness validation
    $bankToEdit = Bank::factory()->create(['name' => 'Another Bank']);
    livewire(EditBank::class, ['record' => $bankToEdit->getKey()])
        ->fillForm([
            'name' => 'Unique Bank', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Bank without changing name to trigger uniqueness validation', function () {
    $bank = Bank::factory()->create(['name' => 'Existing Bank']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Bank'));

    livewire(EditBank::class, ['record' => $bank->getKey()])
        ->fillForm([
            'name' => 'Existing Bank', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('banks', [
        'id' => $bank->id,
        'name' => 'Existing Bank',
    ]);
});

it('autofocus the name field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Bank'));

    // Test CreateBank autofocus
    livewire(CreateBank::class)
        ->assertSeeHtml('autofocus');

    // Test EditBank autofocus
    $bank = Bank::factory()->create();
    livewire(EditBank::class, ['record' => $bank->getKey()])
        ->assertSeeHtml('autofocus');
});
