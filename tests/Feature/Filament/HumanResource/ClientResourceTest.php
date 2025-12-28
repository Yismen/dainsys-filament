<?php

use App\Models\User;
use App\Models\Client;
use App\Models\Permission;
use function Livewire\before;

use Filament\Facades\Filament;
use function Pest\Livewire\livewire;
use function Pest\Laravel\{actingAs, get};
use phpDocumentor\Reflection\PseudoTypes\List_;
use App\Filament\HumanResource\Resources\Clients\Pages\EditClient;
use App\Filament\HumanResource\Resources\Clients\Pages\ViewClient;
use App\Filament\HumanResource\Resources\Clients\Pages\ListClients;
use App\Filament\HumanResource\Resources\Clients\Pages\CreateClient;

beforeEach(function () {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'), // Where `app` is the ID of the panel you want to test.
    );
    $client = Client::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListClients::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateClient::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditClient::getRouteName(),
            'params' => ['record' => $client->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewClient::getRouteName(),
            'params' => ['record' => $client->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];
});

it('require users to be authenticated to access Client resource pages', function (string $method) {
    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index' ,
    'create' ,
    'edit',
    'view',
]);

it('require users to have correct permissions to access Client resource pages', function (string $method) {
    actingAs(User::factory()->create());

    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));
    $response->assertForbidden();
})->with([
    'index' ,
    'create' ,
    'edit',
    'view',
]);

it('allows super admin users to access Client resource pages', function (string $method) {
    actingAs($this->createSuperAdminUser());

    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index' ,
    'create' ,
    'edit',
    'view',
]);

it('allow users with correct permissions to access Client resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions( $this->resource_routes[$method]['permission'], 'Client'));

    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Client list page correctly', function () {
    $clients = Client::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Client'));

    livewire(ListClients::class)
        ->assertCanSeeTableRecords($clients);
});

test('create Client page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Client'));

    $name = 'new Client';
    livewire(CreateClient::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create');

    $this->assertDatabaseHas('clients', [
        'name' => $name,
    ]);
});

test('edit Client page works correctly', function () {
    $client = Client::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Client'));

    $newName = 'Updated Client Name';
    livewire(EditClient::class, ['record' => $client->getKey()])
        ->fillForm([
            'name' => $newName,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('clients', [
        'id' => $client->id,
        'name' => $newName,
    ]);
});

test('form validation require fields on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Client'));

    // Test CreateClient validation
    livewire(CreateClient::class)
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
    // Test EditClient validation
    $client = Client::factory()->create();
    livewire(EditClient::class, ['record' => $client->getKey()])
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

test('Client name must be unique on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Client'));

    $existingClient = Client::factory()->create(['name' => 'Unique Client']);

    // Test CreateClient uniqueness validation
    livewire(CreateClient::class)
        ->fillForm([
            'name' => 'Unique Client', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
    // Test EditClient uniqueness validation
    $clientToEdit = Client::factory()->create(['name' => 'Another Client']);
    livewire(EditClient::class, ['record' => $clientToEdit->getKey()])
        ->fillForm([
            'name' => 'Unique Client', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Client without changing name to trigger uniqueness validation', function () {
    $client = Client::factory()->create(['name' => 'Existing Client']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Client'));

    livewire(EditClient::class, ['record' => $client->getKey()])
        ->fillForm([
            'name' => 'Existing Client', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('clients', [
        'id' => $client->id,
        'name' => 'Existing Client',
    ]);
});

it('autofocus the name field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Client'));

    // Test CreateClient autofocus
    livewire(CreateClient::class)
        ->assertSeeHtml('autofocus');

    // Test EditClient autofocus
    $client = Client::factory()->create();
    livewire(EditClient::class, ['record' => $client->getKey()])
        ->assertSeeHtml('autofocus');
});
