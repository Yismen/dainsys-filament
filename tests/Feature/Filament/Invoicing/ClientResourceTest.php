<?php

use App\Filament\Invoicing\Resources\Clients\Pages\ManageClients;
use App\Models\Client;
use App\Models\User;
use App\Services\InvoiceTemplatesService;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(Filament::getPanel('invoicing'));

    Cache::forget('invoice_template_services');
    $invoiceTemplateOptions = InvoiceTemplatesService::make();
    $invoiceTemplate = array_key_first($invoiceTemplateOptions);

    $this->resource_routes = [
        'index' => [
            'route' => ManageClients::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
    ];

    $this->form_data = [
        'name' => 'Acme Corp',
        'person_of_contact' => 'Jane Doe',
        'phone' => '8091234567',
        'email' => 'billing@acme.test',
        'website' => 'https://acme.test',
        'description' => 'Client for invoicing panel tests.',
        'invoice_template' => $invoiceTemplate,
        'date_field_name' => 'invoice_date',
        'project_field_name' => 'project_code',
    ];

    $this->persisted_form_data = [
        'name' => $this->form_data['name'],
        'invoice_template' => $this->form_data['invoice_template'],
        'date_field_name' => $this->form_data['date_field_name'],
        'project_field_name' => $this->form_data['project_field_name'],
    ];
});

it('require users to be authenticated to access Client resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.invoicing.auth.login'));
})->with([
    'index',
]);

it('require users to have correct permissions to access Client resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertForbidden();
})->with([
    'index',
]);

it('allows super admin users to access Client resource pages', function (string $method): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
]);

it('allow users with correct permissions to access Client resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Client'));

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
]);

it('displays Client list page correctly', function (): void {
    $clients = Client::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Client'));

    livewire(ManageClients::class)
        ->assertCanSeeTableRecords($clients);
});

test('creates Client from modal action', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Client'));

    livewire(ManageClients::class)
        ->callAction('create', data: $this->form_data)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('clients', $this->persisted_form_data);
});

test('edits Client from modal action', function (): void {
    $client = Client::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Client'));

    livewire(ManageClients::class)
        ->callAction('edit', $client->getKey(), $this->form_data)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('clients', array_merge(['id' => $client->id], $this->persisted_form_data));
});

test('form validation requires name on create and edit modal actions', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Client'));

    livewire(ManageClients::class)
        ->callAction('create', data: [
            'name' => '',
        ])
        ->assertHasTableActionErrors(['name' => 'required']);

    $client = Client::factory()->create();

    livewire(ManageClients::class)
        ->callAction('edit', $client->getKey(), [
            'name' => '',
        ])
        ->assertHasTableActionErrors(['name' => 'required']);
});

test('Client name must be unique on create and edit modal actions', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Client'));

    Client::factory()->create(['name' => 'Unique Client']);

    livewire(ManageClients::class)
        ->callAction('create', data: [
            'name' => 'Unique Client',
        ])
        ->assertHasTableActionErrors(['name' => 'unique']);

    $clientToEdit = Client::factory()->create(['name' => 'Another Client']);

    livewire(ManageClients::class)
        ->callAction('edit', $clientToEdit->getKey(), [
            'name' => 'Unique Client',
        ])
        ->assertHasTableActionErrors(['name' => 'unique']);
});

it('allows updating Client without changing name to trigger uniqueness validation', function (): void {
    $client = Client::factory()->create(['name' => 'Existing Client']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Client'));

    livewire(ManageClients::class)
        ->callAction('edit', $client->getKey(), [
            'name' => 'Existing Client',
        ])
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('clients', [
        'id' => $client->id,
        'name' => 'Existing Client',
    ]);
});

it('opens create client modal from list page', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Client'));

    livewire(ManageClients::class)
        ->mountTableAction('create')
        ->assertOk();
});

it('opens view client modal from list page', function (): void {
    $client = Client::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['view', 'view-any'], 'Client'));

    livewire(ManageClients::class)
        ->mountTableAction('view', $client->getKey())
        ->assertOk();
});

it('opens edit client modal from list page', function (): void {
    $client = Client::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Client'));

    livewire(ManageClients::class)
        ->mountTableAction('edit', $client->getKey())
        ->assertOk();
});
