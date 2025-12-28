<?php

use App\Models\Afp;
use App\Models\User;
use App\Models\Permission;
use function Livewire\before;

use Filament\Facades\Filament;
use function Pest\Livewire\livewire;
use function Pest\Laravel\{actingAs, get};
use App\Filament\HumanResource\Resources\Afps\Pages\EditAfp;
use App\Filament\HumanResource\Resources\Afps\Pages\ListAfps;
use App\Filament\HumanResource\Resources\Afps\Pages\CreateAfp;

beforeEach(function () {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'), // Where `app` is the ID of the panel you want to test.
    );
    $afp = Afp::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => 'filament.human-resource.resources.afps.index',
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => 'filament.human-resource.resources.afps.create',
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => 'filament.human-resource.resources.afps.edit',
            'params' => ['record' => $afp->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => 'filament.human-resource.resources.afps.view',
            'params' => ['record' => $afp->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];
});

it('require users to be authenticated to access Afp resource pages', function (string $method) {
    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index' ,
    'create' ,
    'edit',
    'view',
]);

it('require users to have correct permissions to access Afp resource pages', function (string $method) {
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

it('allows super admin users to access Afp resource pages', function (string $method) {
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

it('allow users with correct permissions to access Afp resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions( $this->resource_routes[$method]['permission'], 'Afp'));

    $response = get(route( $this->resource_routes[$method]['route'],
    $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Afp list page correctly', function () {
    $afps = Afp::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Afp'));

    livewire(ListAfps::class)
        ->assertCanSeeTableRecords($afps);
});

test('create Afp page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Afp'));

    $name = 'new AFP';
    livewire(CreateAfp::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create');

    $this->assertDatabaseHas('afps', [
        'name' => $name,
    ]);
});

test('edit Afp page works correctly', function () {
    $afp = Afp::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Afp'));

    $newName = 'Updated AFP Name';
    livewire(EditAfp::class, ['record' => $afp->getKey()])
        ->fillForm([
            'name' => $newName,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('afps', [
        'id' => $afp->id,
        'name' => $newName,
    ]);
});

test('form validation require fields on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Afp'));

    // Test CreateAfp validation
    livewire(CreateAfp::class)
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
    // Test EditAfp validation
    $afp = Afp::factory()->create();
    livewire(EditAfp::class, ['record' => $afp->getKey()])
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

test('Afp name must be unique on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Afp'));

    $existingAfp = Afp::factory()->create(['name' => 'Unique AFP']);

    // Test CreateAfp uniqueness validation
    livewire(CreateAfp::class)
        ->fillForm([
            'name' => 'Unique AFP', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
    // Test EditAfp uniqueness validation
    $afpToEdit = Afp::factory()->create(['name' => 'Another AFP']);
    livewire(EditAfp::class, ['record' => $afpToEdit->getKey()])
        ->fillForm([
            'name' => 'Unique AFP', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Afp without changing name to trigger uniqueness validation', function () {
    $afp = Afp::factory()->create(['name' => 'Existing AFP']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Afp'));

    livewire(EditAfp::class, ['record' => $afp->getKey()])
        ->fillForm([
            'name' => 'Existing AFP', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('afps', [
        'id' => $afp->id,
        'name' => 'Existing AFP',
    ]);
});

it('autofocus the name field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Afp'));

    // Test CreateAfp autofocus
    livewire(CreateAfp::class)
        ->assertSeeHtml('autofocus');

    // Test EditAfp autofocus
    $afp = Afp::factory()->create();
    livewire(EditAfp::class, ['record' => $afp->getKey()])
        ->assertSeeHtml('autofocus');
});
