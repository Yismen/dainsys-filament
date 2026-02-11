<?php

use App\Filament\HumanResource\Resources\Citizenships\Pages\CreateCitizenship;
use App\Filament\HumanResource\Resources\Citizenships\Pages\EditCitizenship;
use App\Filament\HumanResource\Resources\Citizenships\Pages\ListCitizenships;
use App\Filament\HumanResource\Resources\Citizenships\Pages\ViewCitizenship;
use App\Models\Citizenship;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'), // Where `app` is the ID of the panel you want to test.
    );
    $citizenship = Citizenship::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListCitizenships::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateCitizenship::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditCitizenship::getRouteName(),
            'params' => ['record' => $citizenship->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewCitizenship::getRouteName(),
            'params' => ['record' => $citizenship->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];
});

it('require users to be authenticated to access Citizenship resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Citizenship resource pages', function (string $method): void {
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

it('allows super admin users to access Citizenship resource pages', function (string $method): void {
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

it('allow users with correct permissions to access Citizenship resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Citizenship'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Citizenship list page correctly', function (): void {
    $citizenships = Citizenship::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Citizenship'));

    livewire(ListCitizenships::class)
        ->assertCanSeeTableRecords($citizenships);
});

test('create Citizenship page works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Citizenship'));

    $name = 'new Citizenship';
    livewire(CreateCitizenship::class)
        ->fillForm([
            'name' => $name,
        ])
        ->call('create');

    $this->assertDatabaseHas('citizenships', [
        'name' => $name,
    ]);
});

test('edit Citizenship page works correctly', function (): void {
    $citizenship = Citizenship::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Citizenship'));

    $newName = 'Updated Citizenship Name';
    livewire(EditCitizenship::class, ['record' => $citizenship->getKey()])
        ->fillForm([
            'name' => $newName,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('citizenships', [
        'id' => $citizenship->id,
        'name' => $newName,
    ]);
});

test('form validation require fields on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Citizenship'));

    // Test CreateCitizenship validation
    livewire(CreateCitizenship::class)
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
    // Test EditCitizenship validation
    $citizenship = Citizenship::factory()->create();
    livewire(EditCitizenship::class, ['record' => $citizenship->getKey()])
        ->fillForm([
            'name' => '', // Invalid: name is required
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

test('Citizenship name must be unique on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Citizenship'));

    $existingCitizenship = Citizenship::factory()->create(['name' => 'Unique Citizenship']);

    // Test CreateCitizenship uniqueness validation
    livewire(CreateCitizenship::class)
        ->fillForm([
            'name' => 'Unique Citizenship', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
    // Test EditCitizenship uniqueness validation
    $citizenshipToEdit = Citizenship::factory()->create(['name' => 'Another Citizenship']);
    livewire(EditCitizenship::class, ['record' => $citizenshipToEdit->getKey()])
        ->fillForm([
            'name' => 'Unique Citizenship', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('allows updating Citizenship without changing name to trigger uniqueness validation', function (): void {
    $citizenship = Citizenship::factory()->create(['name' => 'Existing Citizenship']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Citizenship'));

    livewire(EditCitizenship::class, ['record' => $citizenship->getKey()])
        ->fillForm([
            'name' => 'Existing Citizenship', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('citizenships', [
        'id' => $citizenship->id,
        'name' => 'Existing Citizenship',
    ]);
});

it('autofocus the name field on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Citizenship'));

    // Test CreateCitizenship autofocus
    livewire(CreateCitizenship::class)
        ->assertSeeHtml('autofocus');

    // Test EditCitizenship autofocus
    $citizenship = Citizenship::factory()->create();
    livewire(EditCitizenship::class, ['record' => $citizenship->getKey()])
        ->assertSeeHtml('autofocus');
});
