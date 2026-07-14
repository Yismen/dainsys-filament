<?php

use App\Filament\Workforce\Resources\Incentives\Pages\ManageIncentives;
use App\Models\Incentive;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('workforce'),
    );

    $incentive = Incentive::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ManageIncentives::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
    ];
});

it('require users to be authenticated to access Incentive resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.workforce.auth.login'));
})->with([
    'index',
]);

it('require users to have correct permissions to access Incentive resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertForbidden();
})->with([
    'index',
]);

it('allows super admin users to access Incentive resource pages', function (string $method): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
]);

it('allow users with correct permissions to access Incentive resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Incentive'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
]);

it('displays Incentive list page correctly', function (): void {
    Incentive::factory()->create();
    $incentives = Incentive::get();

    actingAs($this->createUserWithPermissionTo('view-any Incentive'));

    livewire(ManageIncentives::class)
        ->assertCanSeeTableRecords($incentives);
});

it('deletes incentives by payable date', function (): void {
    $futureDate = now()->addYear()->format('Y-m-d');

    Incentive::factory()->count(3)->create([
        'payable_date' => $futureDate,
    ]);

    actingAs($this->createUserWithPermissionTo('view-any Incentive'));

    livewire(ManageIncentives::class)
        ->callTableAction('deleteByPayableDate', null, [
            'payable_date' => $futureDate,
        ])
        ->assertNotified();

    expect(Incentive::query()
        ->whereDate('payable_date', $futureDate)
        ->count()
    )->toBe(0);
});
