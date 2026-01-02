<?php

use App\Filament\HumanResource\Resources\Sites\Pages\CreateSite;
use App\Filament\HumanResource\Resources\Sites\Pages\EditSite;
use App\Filament\HumanResource\Resources\Sites\Pages\ListSites;
use App\Filament\HumanResource\Resources\Sites\Pages\ViewSite;
use App\Models\Site;
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
    $site = Site::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListSites::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateSite::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditSite::getRouteName(),
            'params' => ['record' => $site->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewSite::getRouteName(),
            'params' => ['record' => $site->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];

    $this->form_data = [
        'name' => 'new name',
        'person_of_contact' => 'new person_of_contact',
        'phone' => '8456665555',
        'email' => 'email@mail.com',
        'address' => 'new address',
        'geolocation' => 'new geolocation',
        'description' => 'new description',
    ];
});

it('require users to be authenticated to access Site resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Site resource pages', function (string $method) {
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

it('allows super admin users to access Site resource pages', function (string $method) {
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

it('allow users with correct permissions to access Site resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Site'));

    $response = get(route($this->resource_routes[$method]['route'],
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

    livewire(CreateSite::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('sites', $this->form_data);
});

test('edit Site page works correctly', function () {
    $site = Site::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Site'));

    livewire(EditSite::class, ['record' => $site->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('sites', array_merge(['id' => $site->id], $this->form_data));
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
