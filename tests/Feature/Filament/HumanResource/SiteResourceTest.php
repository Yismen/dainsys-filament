<?php

use App\Models\Site;
use App\Models\User;
use App\Models\Permission;
use function Livewire\before;

use Filament\Facades\Filament;
use function Pest\Livewire\livewire;
use function Pest\Laravel\{actingAs, get};
use App\Filament\HumanResource\Resources\Sites\Pages\EditSite;
use App\Filament\HumanResource\Resources\Sites\Pages\ListSites;
use App\Filament\HumanResource\Resources\Sites\Pages\CreateSite;

beforeEach(function () {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'), // Where `app` is the ID of the panel you want to test.
    );
    $site = Site::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => 'filament.human-resource.resources.sites.index',
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => 'filament.human-resource.resources.sites.create',
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => 'filament.human-resource.resources.sites.edit',
            'params' => ['record' => $site->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => 'filament.human-resource.resources.sites.view',
            'params' => ['record' => $site->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];
});

it('require users to be authenticated to access Site resource pages', function (string $method) {
    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index' ,
    'create' ,
    'edit',
    'view',
]);

it('require users to have correct permissions to access Site resource pages', function (string $method) {
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

it('allows super admin users to access Site resource pages', function (string $method) {
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

it('allow users with correct permissions to access Site resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions( $this->resource_routes[$method]['permission'], 'Site'));

    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Site list page correctly', function () {
    $sites = Site::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Site'));

    livewire(ListSites::class)
        ->assertCanSeeTableRecords($sites);
});

test('create Site page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Site'));

    $name = 'new Site';
    livewire(CreateSite::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create');

    $this->assertDatabaseHas('sites', [
        'name' => $name,
    ]);
});

test('edit Site page works correctly', function () {
    $site = Site::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Site'));

    $newName = 'Updated Site Name';
    livewire(EditSite::class, ['record' => $site->getKey()])
        ->fillForm([
            'name' => $newName,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('sites', [
        'id' => $site->id,
        'name' => $newName,
    ]);
});

test('form validation require fields on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Site'));

    // Test CreateSite validation
    livewire(CreateSite::class)
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
    // Test EditSite validation
    $site = Site::factory()->create();
    livewire(EditSite::class, ['record' => $site->getKey()])
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

test('Site name must be unique on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Site'));

    $existingSite = Site::factory()->create(['name' => 'Unique Site']);

    // Test CreateSite uniqueness validation
    livewire(CreateSite::class)
        ->fillForm([
            'name' => 'Unique Site', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
    // Test EditSite uniqueness validation
    $siteToEdit = Site::factory()->create(['name' => 'Another Site']);
    livewire(EditSite::class, ['record' => $siteToEdit->getKey()])
        ->fillForm([
            'name' => 'Unique Site', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Site without changing name to trigger uniqueness validation', function () {
    $site = Site::factory()->create(['name' => 'Existing Site']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Site'));

    livewire(EditSite::class, ['record' => $site->getKey()])
        ->fillForm([
            'name' => 'Existing Site', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('sites', [
        'id' => $site->id,
        'name' => 'Existing Site',
    ]);
});

it('autofocus the name field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Site'));

    // Test CreateSite autofocus
    livewire(CreateSite::class)
        ->assertSeeHtml('autofocus');

    // Test EditSite autofocus
    $site = Site::factory()->create();
    livewire(EditSite::class, ['record' => $site->getKey()])
        ->assertSeeHtml('autofocus');
});
