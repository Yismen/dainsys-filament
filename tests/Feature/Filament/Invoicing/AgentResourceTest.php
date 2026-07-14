<?php

use App\Filament\Invoicing\Resources\Agents\Pages\ManageAgents;
use App\Models\InvoiceAgent;
use App\Models\Project;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(Filament::getPanel('invoicing'));

    $this->resource_routes = [
        'index' => [
            'route' => ManageAgents::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
    ];

    $this->form_data = [
        'name' => 'Billing Agent',
        'project_id' => Project::factory()->create()->id,
        'phone' => '8091234567',
        'email' => 'agent@example.test',
    ];
});

it('require users to be authenticated to access Agent resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.invoicing.auth.login'));
})->with(['index']);

it('require users to have correct permissions to access Agent resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertForbidden();
})->with(['index']);

it('allows super admin users to access Agent resource pages', function (string $method): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('allow users with correct permissions to access Agent resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'InvoiceAgent'));

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('displays Agent list page correctly', function (): void {
    $agents = InvoiceAgent::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any InvoiceAgent'));

    livewire(ManageAgents::class)
        ->assertCanSeeTableRecords($agents);
});

test('creates Agent from modal action', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'InvoiceAgent'));

    livewire(ManageAgents::class)
        ->callAction('create', data: $this->form_data)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('invoice_agents', $this->form_data);
});

test('edits Agent from modal action', function (): void {
    $agent = InvoiceAgent::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'InvoiceAgent'));

    livewire(ManageAgents::class)
        ->callAction('edit', $agent->getKey(), $this->form_data)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('invoice_agents', array_merge(['id' => $agent->id], $this->form_data));
});

test('form validation requires fields on create and edit modal actions', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'InvoiceAgent'));

    livewire(ManageAgents::class)
        ->callAction('create', data: [
            'name' => '',
            'project_id' => null,
        ])
        ->assertHasTableActionErrors([
            'name' => 'required',
            'project_id' => 'required',
        ]);

    $agent = InvoiceAgent::factory()->create();

    livewire(ManageAgents::class)
        ->callAction('edit', $agent->getKey(), [
            'name' => '',
            'project_id' => null,
        ])
        ->assertHasTableActionErrors([
            'name' => 'required',
            'project_id' => 'required',
        ]);
});

it('opens create, view and edit agent modals from list page', function (): void {
    $agent = InvoiceAgent::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['create', 'view', 'update', 'view-any'], 'InvoiceAgent'));

    livewire(ManageAgents::class)
        ->mountTableAction('create')
        ->assertOk()
        ->mountTableAction('view', $agent->getKey())
        ->assertOk()
        ->mountTableAction('edit', $agent->getKey())
        ->assertOk();
});
