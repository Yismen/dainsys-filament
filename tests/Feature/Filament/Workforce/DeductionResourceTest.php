<?php

use App\Filament\Workforce\Resources\Deductions\Pages\ManageDeductions;
use App\Models\Deduction;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('workforce'),
    );

    Deduction::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ManageDeductions::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
    ];
});

it('require users to be authenticated to access Deduction resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.workforce.auth.login'));
})->with([
    'index',
]);

it('require users to have correct permissions to access Deduction resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertForbidden();
})->with([
    'index',
]);

it('allows super admin users to access Deduction resource pages', function (string $method): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
]);

it('allow users with correct permissions to access Deduction resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Deduction'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
]);

it('displays Deduction list page correctly', function (): void {
    Deduction::factory()->create();
    $deductions = Deduction::get();

    actingAs($this->createUserWithPermissionTo('view-any Deduction'));

    livewire(ManageDeductions::class)
        ->assertCanSeeTableRecords($deductions);
});
