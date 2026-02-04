<?php

use App\Filament\Workforce\Resources\Payrolls\Pages\ManagePayrolls;
use App\Models\Payroll;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('workforce'),
    );

    $payroll = Payroll::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ManagePayrolls::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
    ];
});

it('require users to be authenticated to access Payroll resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.workforce.auth.login'));
})->with([
    'index',
]);

it('require users to have correct permissions to access Payroll resource pages', function (string $method) {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertForbidden();
})->with([
    'index',
]);

it('allows super admin users to access Payroll resource pages', function (string $method) {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
]);

it('allow users with correct permissions to access Payroll resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Payroll'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
]);

it('displays Payroll list page correctly', function () {
    Payroll::factory()->create();
    $payrolls = Payroll::get();

    actingAs($this->createUserWithPermissionTo('view-any Payroll'));

    livewire(ManagePayrolls::class)
        ->assertCanSeeTableRecords($payrolls);
});
