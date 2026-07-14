<?php

use App\Enums\RevenueTypes;
use App\Filament\Invoicing\Resources\Campaigns\Pages\ManageCampaigns;
use App\Models\Campaign;
use App\Models\InvoiceAgent;
use App\Models\Project;
use App\Models\Source;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(Filament::getPanel('invoicing'));

    $project = Project::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ManageCampaigns::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
    ];

    $this->form_data = [
        'name' => 'Campaign Invoicing',
        'project_id' => $project->id,
        'source_id' => Source::factory()->create()->id,
        'invoice_agent_id' => InvoiceAgent::factory()->create(['project_id' => $project->id])->id,
        'revenue_type' => RevenueTypes::LoginTime->value,
        'sph_goal' => 2.5,
        'revenue_rate' => 3.25,
        'description' => 'Campaign for invoicing tests',
    ];
});

it('require users to be authenticated to access Campaign resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.invoicing.auth.login'));
})->with(['index']);

it('require users to have correct permissions to access Campaign resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertForbidden();
})->with(['index']);

it('allows super admin users to access Campaign resource pages', function (string $method): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('allow users with correct permissions to access Campaign resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Campaign'));

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('displays Campaign list page correctly', function (): void {
    $campaigns = Campaign::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Campaign'));

    livewire(ManageCampaigns::class)
        ->assertCanSeeTableRecords($campaigns);
});

test('creates Campaign from modal action', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Campaign'));

    livewire(ManageCampaigns::class)
        ->callAction('create', data: $this->form_data)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('campaigns', $this->form_data);
});

test('edits Campaign from modal action', function (): void {
    $campaign = Campaign::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Campaign'));

    livewire(ManageCampaigns::class)
        ->callAction('edit', $campaign->getKey(), $this->form_data)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('campaigns', array_merge(['id' => $campaign->id], $this->form_data));
});

test('form validation requires fields on create and edit modal actions', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Campaign'));

    livewire(ManageCampaigns::class)
        ->callAction('create', data: [
            'name' => '',
            'project_id' => null,
            'source_id' => null,
            'revenue_type' => null,
            'sph_goal' => null,
            'revenue_rate' => null,
        ])
        ->assertHasTableActionErrors([
            'name' => 'required',
            'project_id' => 'required',
            'source_id' => 'required',
            'revenue_type' => 'required',
            'sph_goal' => 'required',
            'revenue_rate' => 'required',
        ]);

    $campaign = Campaign::factory()->create();

    livewire(ManageCampaigns::class)
        ->callAction('edit', $campaign->getKey(), [
            'name' => '',
            'project_id' => null,
            'source_id' => null,
            'revenue_type' => null,
            'sph_goal' => null,
            'revenue_rate' => null,
        ])
        ->assertHasTableActionErrors([
            'name' => 'required',
            'project_id' => 'required',
            'source_id' => 'required',
            'revenue_type' => 'required',
            'sph_goal' => 'required',
            'revenue_rate' => 'required',
        ]);
});

test('Campaign name must be unique on create and edit modal actions', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Campaign'));

    Campaign::factory()->create(['name' => 'Unique Campaign']);

    livewire(ManageCampaigns::class)
        ->callAction('create', data: [
            'name' => 'Unique Campaign',
            'project_id' => Project::factory()->create()->id,
            'source_id' => Source::factory()->create()->id,
            'revenue_type' => RevenueTypes::LoginTime->value,
            'sph_goal' => 2,
            'revenue_rate' => 3,
        ])
        ->assertHasTableActionErrors(['name' => 'unique']);

    $campaignToEdit = Campaign::factory()->create(['name' => 'Another Campaign']);

    livewire(ManageCampaigns::class)
        ->callAction('edit', $campaignToEdit->getKey(), [
            'name' => 'Unique Campaign',
            'project_id' => Project::factory()->create()->id,
            'source_id' => Source::factory()->create()->id,
            'revenue_type' => RevenueTypes::LoginTime->value,
            'sph_goal' => 2,
            'revenue_rate' => 3,
        ])
        ->assertHasTableActionErrors(['name' => 'unique']);
});

it('opens create, view and edit campaign modals from list page', function (): void {
    $campaign = Campaign::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['create', 'view', 'update', 'view-any'], 'Campaign'));

    livewire(ManageCampaigns::class)
        ->mountTableAction('create')
        ->assertOk()
        ->mountTableAction('view', $campaign->getKey())
        ->assertOk()
        ->mountTableAction('edit', $campaign->getKey())
        ->assertOk();
});
