<?php

use App\Filament\HumanResource\Resources\Holidays\Pages\CreateHoliday;
use App\Filament\HumanResource\Resources\Holidays\Pages\EditHoliday;
use App\Filament\HumanResource\Resources\Holidays\Pages\ListHolidays;
use App\Filament\HumanResource\Resources\Holidays\Pages\ViewHoliday;
use App\Models\Holiday;
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
    $holiday = Holiday::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListHolidays::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateHoliday::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditHoliday::getRouteName(),
            'params' => ['record' => $holiday->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewHoliday::getRouteName(),
            'params' => ['record' => $holiday->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];
});

it('require users to be authenticated to access Holiday resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Holiday resource pages', function (string $method): void {
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

it('allows super admin users to access Holiday resource pages', function (string $method): void {
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

it('allow users with correct permissions to access Holiday resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Holiday'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays Holiday list page correctly', function (): void {
    $holidays = Holiday::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Holiday'));

    livewire(ListHolidays::class)
        ->assertCanSeeTableRecords($holidays);
});

test('create Holiday page works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Holiday'));

    $name = 'new Holiday';
    livewire(CreateHoliday::class)
        ->fillForm([
            'name' => $name,
            'date' => now()->addDays(10)->format('Y-m-d'),
            'description' => 'Holiday description',
        ])
        ->call('create');

    $this->assertDatabaseHas('holidays', [
        'name' => $name,
    ]);
});

test('edit Holiday page works correctly', function (): void {
    $holiday = Holiday::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Holiday'));

    $newName = 'Updated Holiday Name';
    livewire(EditHoliday::class, ['record' => $holiday->getKey()])
        ->fillForm([
            'name' => $newName,
            'date' => $holiday->date,
            'description' => $holiday->description,
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('holidays', [
        'id' => $holiday->id,
        'name' => $newName,
    ]);
});

test('form validation require fields on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Holiday'));

    // Test CreateHoliday validation
    livewire(CreateHoliday::class)
        ->fillForm([
            'name' => '', // Invalid: name is required
            'date' => '', // Invalid: date is required
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'date' => 'required']);
    // Test EditHoliday validation
    $holiday = Holiday::factory()->create();
    livewire(EditHoliday::class, ['record' => $holiday->getKey()])
        ->fillForm([
            'name' => '', // Invalid: name is required
            'date' => '', // Invalid: date is required
        ])
        ->call('save')
        ->assertHasFormErrors(keys: ['name' => 'required', 'date' => 'required']);
});

test('Holiday date must be unique on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Holiday'));

    $existingHoliday = Holiday::factory()->create(['date' => now()->format('Y-m-d')]);

    // Test CreateHoliday uniqueness validation
    livewire(CreateHoliday::class)
        ->fillForm([
            'name' => 'Unique Holiday', // Invalid: name must be unique
            'date' => now()->format('Y-m-d'), // Invalid: date must be unique
        ])
        ->call('create')
        ->assertHasFormErrors(['date' => 'unique']);
    // Test EditHoliday uniqueness validation
    $holidayToEdit = Holiday::factory()->create(['name' => 'Another Holiday']);
    livewire(EditHoliday::class, params: ['record' => $holidayToEdit->getKey()])
        ->fillForm([
            'date' => now()->format('Y-m-d'), // Invalid: date must be unique
        ])
        ->call('save')
        ->assertHasFormErrors(['date' => 'unique']);
});

it('allows updating Holiday without changing name to trigger uniqueness validation', function (): void {
    $holiday = Holiday::factory()->create(['name' => 'Existing Holiday']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Holiday'));

    livewire(EditHoliday::class, ['record' => $holiday->getKey()])
        ->fillForm([
            'date' => $holiday->date, // Same date, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('holidays', [
        'id' => $holiday->id,
        'date' => $holiday->date,
    ]);
});

it('autofocus the name field on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Holiday'));

    // Test CreateHoliday autofocus
    livewire(CreateHoliday::class)
        ->assertSeeHtml('autofocus');

    // Test EditHoliday autofocus
    $holiday = Holiday::factory()->create();
    livewire(EditHoliday::class, ['record' => $holiday->getKey()])
        ->assertSeeHtml('autofocus');
});
