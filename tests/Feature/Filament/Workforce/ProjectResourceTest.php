<?php

use App\Filament\Workforce\Resources\Projects\Pages\CreateProject;
use App\Filament\Workforce\Resources\Projects\Pages\EditProject;
use App\Filament\Workforce\Resources\Projects\Pages\ListProjects;
use App\Filament\Workforce\Resources\Projects\Pages\ViewProject;
use App\Models\Client;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

function createProjectExecutiveUser(?string $name = null): User
{
    $role = Role::firstOrCreate(['name' => 'Project Executive Manager', 'guard_name' => 'web']);
    $user = User::factory()->create(
        $name ? ['name' => $name] : []
    );
    $user->assignRole($role);

    return $user;
}

beforeEach(function (): void {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('workforce'), // Where `app` is the ID of the panel you want to test.
    );
    $project = Project::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListProjects::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateProject::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditProject::getRouteName(),
            'params' => ['record' => $project->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewProject::getRouteName(),
            'params' => ['record' => $project->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];
});

it('require users to be authenticated to access Project resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.workforce.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Project resource pages', function (string $method): void {
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

it('allows super admin users to access Project resource pages', function (string $method): void {
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

it('allow users with correct permissions to access Project resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Project'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Project list page correctly', function (): void {
    $projects = Project::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Project'));

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords($projects);
});

test('create Project page works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Project'));

    $name = 'new Project';
    $manager = createProjectExecutiveUser();

    livewire(CreateProject::class)
        ->fillForm([
            'name' => $name,
            'client_id' => Client::factory()->create()->id,
            'manager_id' => $manager->id,
        ])
        ->call('create');

    $this->assertDatabaseHas('projects', [
        'name' => $name,
        'manager_id' => $manager->id,
    ]);
});

test('edit Project page works correctly', function (): void {
    $project = Project::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Project'));

    $newName = 'Updated Project Name';
    $manager = createProjectExecutiveUser();

    livewire(EditProject::class, ['record' => $project->getKey()])
        ->fillForm([
            'name' => $newName,
            'manager_id' => $manager->id,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'name' => $newName,
        'manager_id' => $manager->id,
    ]);
});

it('displays the assigned manager on view project page', function (): void {
    $manager = createProjectExecutiveUser('Manager Example');
    $project = Project::factory()->create([
        'manager_id' => $manager->id,
    ]);

    actingAs($this->createUserWithPermissionsToActions(['view', 'view-any'], 'Project'));

    livewire(ViewProject::class, ['record' => $project->getKey()])
        ->assertSee('Manager')
        ->assertSee('Manager Example');
});

test('form validation require fields on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Project'));

    // Test CreateProject validation
    livewire(CreateProject::class)
        ->fillForm([
            'name' => '', // Invalid: name is required
            'client_id' => '', // Invalid: client_id is required
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'client_id' => 'required',
        ]);
    // Test EditProject validation
    $project = Project::factory()->create();
    livewire(EditProject::class, ['record' => $project->getKey()])
        ->fillForm([
            'name' => '', // Invalid: name is required
            'client_id' => '', // Invalid: client_id is required
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

test('Project name must be unique on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Project'));

    $existingProject = Project::factory()->create(['name' => 'Unique Project']);

    // Test CreateProject uniqueness validation
    livewire(CreateProject::class)
        ->fillForm([
            'name' => 'Unique Project', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
    // Test EditProject uniqueness validation
    $projectToEdit = Project::factory()->create(['name' => 'Another Project']);
    livewire(EditProject::class, ['record' => $projectToEdit->getKey()])
        ->fillForm([
            'name' => 'Unique Project', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Project without changing name to trigger uniqueness validation', function (): void {
    $project = Project::factory()->create(['name' => 'Existing Project']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Project'));

    livewire(EditProject::class, ['record' => $project->getKey()])
        ->fillForm([
            'name' => 'Existing Project', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'name' => 'Existing Project',
    ]);
});

it('autofocus the name field on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Project'));

    // Test CreateProject autofocus
    livewire(CreateProject::class)
        ->assertSeeHtml('autofocus');

    // Test EditProject autofocus
    $project = Project::factory()->create();
    livewire(EditProject::class, ['record' => $project->getKey()])
        ->assertSeeHtml('autofocus');
});
