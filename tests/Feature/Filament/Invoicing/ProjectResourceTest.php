<?php

use App\Filament\Invoicing\Resources\Projects\Pages\ManageProjects;
use App\Models\Client;
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
            'route' => ManageProjects::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
    ];

    $this->form_data = [
        'name' => 'Project Invoicing',
        'client_id' => Client::factory()->create()->id,
        'manager_id' => null,
        'address' => 'Santo Domingo',
        'invoice_notes' => 'Default invoice notes',
        'invoice_net_days' => 30,
        'description' => 'Project for invoicing panel tests',
    ];

    $this->persisted_form_data = [
        'name' => $this->form_data['name'],
        'client_id' => $this->form_data['client_id'],
        'manager_id' => $this->form_data['manager_id'],
        'address' => '<p>'.$this->form_data['address'].'</p>',
        'invoice_notes' => '<p>'.$this->form_data['invoice_notes'].'</p>',
        'invoice_net_days' => $this->form_data['invoice_net_days'],
        'description' => $this->form_data['description'],
    ];
});

it('require users to be authenticated to access Project resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.invoicing.auth.login'));
})->with(['index']);

it('require users to have correct permissions to access Project resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertForbidden();
})->with(['index']);

it('allows super admin users to access Project resource pages', function (string $method): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('allow users with correct permissions to access Project resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Project'));

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('displays Project list page correctly', function (): void {
    $projects = Project::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Project'));

    livewire(ManageProjects::class)
        ->assertCanSeeTableRecords($projects);
});

test('creates Project from modal action', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Project'));

    livewire(ManageProjects::class)
        ->callTableAction('create', data: $this->form_data)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('projects', $this->persisted_form_data);
});

test('edits Project from modal action', function (): void {
    $project = Project::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Project'));

    livewire(ManageProjects::class)
        ->callTableAction('edit', $project->getKey(), $this->form_data)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('projects', array_merge(['id' => $project->id], $this->persisted_form_data));
});

test('form validation requires fields on create and edit modal actions', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Project'));

    livewire(ManageProjects::class)
        ->callTableAction('create', data: [
            'name' => '',
            'client_id' => null,
        ])
        ->assertHasTableActionErrors([
            'name' => 'required',
            'client_id' => 'required',
        ]);

    $project = Project::factory()->create();

    livewire(ManageProjects::class)
        ->callTableAction('edit', $project->getKey(), [
            'name' => '',
            'client_id' => null,
        ])
        ->assertHasTableActionErrors([
            'name' => 'required',
            'client_id' => 'required',
        ]);
});

test('Project name must be unique on create and edit modal actions', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Project'));

    Project::factory()->create(['name' => 'Unique Project']);

    livewire(ManageProjects::class)
        ->callTableAction('create', data: [
            'name' => 'Unique Project',
            'client_id' => Client::factory()->create()->id,
            'address' => 'Santo Domingo',
            'invoice_notes' => 'Unique project invoice notes',
            'invoice_net_days' => 30,
        ])
        ->assertHasTableActionErrors(['name' => 'unique']);

    $projectToEdit = Project::factory()->create(['name' => 'Another Project']);

    livewire(ManageProjects::class)
        ->callTableAction('edit', $projectToEdit->getKey(), [
            'name' => 'Unique Project',
            'client_id' => Client::factory()->create()->id,
            'address' => 'Santo Domingo',
            'invoice_notes' => 'Unique project invoice notes',
            'invoice_net_days' => 30,
        ])
        ->assertHasTableActionErrors(['name' => 'unique']);
});

it('opens create, view and edit project modals from list page', function (): void {
    $project = Project::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['create', 'view', 'update', 'view-any'], 'Project'));

    livewire(ManageProjects::class)
        ->mountTableAction('create')
        ->assertOk()
        ->mountTableAction('view', $project->getKey())
        ->assertOk()
        ->mountTableAction('edit', $project->getKey())
        ->assertOk();
});
