<?php

use App\Filament\Workforce\Resources\NightlyHours\Pages\CreateNightlyHour;
use App\Filament\Workforce\Resources\NightlyHours\Pages\EditNightlyHour;
use App\Filament\Workforce\Resources\NightlyHours\Pages\ListNightlyHours;
use App\Filament\Workforce\Resources\NightlyHours\Pages\ViewNightlyHour;
use App\Models\Employee;
use App\Models\NightlyHour;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('workforce'),
    );

    $this->employee = Employee::factory()->create();
    $this->nightlyHour = NightlyHour::factory()->create([
        'employee_id' => $this->employee->id,
    ]);

    $this->form_data = [
        'employee_id' => $this->employee->id,
        'date' => now()->format('Y-m-d'),
        'total_hours' => 4.5,
    ];

    $this->resource_routes = [
        'index' => [
            'route' => ListNightlyHours::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        // 'create' => [
        //     'route' => CreateNightlyHour::getRouteName(),
        //     'params' => [],
        //     'permission' => ['create', 'view-any'],
        // ],
        // 'edit' => [
        //     'route' => EditNightlyHour::getRouteName(),
        //     'params' => ['record' => $this->nightlyHour->getKey()],
        //     'permission' => ['update', 'edit', 'view-any'],
        // ],
        // 'view' => [
        //     'route' => ViewNightlyHour::getRouteName(),
        //     'params' => ['record' => $this->nightlyHour->getKey()],
        //     'permission' => ['view', 'view-any'],
        // ],
    ];
});

it('require users to be authenticated to access NightlyHour resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.workforce.auth.login'));
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('require users to have correct permissions to access NightlyHour resource pages', function (string $method) {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));
    $response->assertForbidden();
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('allows super admin users to access NightlyHour resource pages', function (string $method) {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertSuccessful();
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('allows users with correct permissions to access NightlyHour resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'NightlyHour'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertSuccessful();
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('can list nightly hours', function () {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'NightlyHour'));

    $nightly = NightlyHour::factory()->create();

    livewire(ListNightlyHours::class)
        ->assertSuccessful();
});

it('can create nightly hour', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'NightlyHour'));

    livewire(CreateNightlyHour::class)
        ->fillForm($this->form_data)
        ->call('create')
        ->assertNotified();

    $this->assertDatabaseHas(NightlyHour::class, [
        'employee_id' => $this->employee->id,
        'date' => $this->form_data['date'],
        'total_hours' => 4.5,
    ]);
});

it('can edit nightly hour', function () {
    $nightlyHour = NightlyHour::factory()->create([
        'employee_id' => $this->employee->id,
        'date' => now()->format('Y-m-d'),
        'total_hours' => 2.0,
    ]);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'NightlyHour'));

    livewire(EditNightlyHour::class, ['record' => $nightlyHour->getKey()])
        ->fillForm([
            'employee_id' => $nightlyHour->employee_id,
            'date' => $nightlyHour->date->format('Y-m-d'),
            'total_hours' => 5.5,
        ])
        ->call('save')
        ->assertNotified();

    expect($nightlyHour->refresh()->total_hours)->toBe(5.5);
});

it('can delete nightly hour', function () {
    $nightlyHour = NightlyHour::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['delete', 'view-any'], 'NightlyHour'));

    livewire(EditNightlyHour::class, ['record' => $nightlyHour->getKey()])
        ->callAction('delete')
        ->assertNotified();

    $this->assertSoftDeleted($nightlyHour);
});
