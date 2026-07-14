<?php

use App\Filament\Invoicing\Resources\Items\Pages\ManageItems;
use App\Models\Campaign;
use App\Models\Item;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(Filament::getPanel('invoicing'));

    $this->resource_routes = [
        'index' => [
            'route' => ManageItems::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
    ];

    $this->form_data = [
        'name' => 'Headset',
        'campaign_id' => Campaign::factory()->create()->id,
        'price' => 49.99,
        'description' => 'Invoicing item description',
    ];
});

it('require users to be authenticated to access Item resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.invoicing.auth.login'));
})->with(['index']);

it('require users to have correct permissions to access Item resource pages', function (string $method): void {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertForbidden();
})->with(['index']);

it('allows super admin users to access Item resource pages', function (string $method): void {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('allow users with correct permissions to access Item resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Item'));

    $response = get(route($this->resource_routes[$method]['route'], $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with(['index']);

it('displays Item list page correctly', function (): void {
    $items = Item::factory()->count(5)->create();

    actingAs($this->createUserWithPermissionTo('view-any Item'));

    livewire(ManageItems::class)
        ->assertCanSeeTableRecords($items);
});

test('creates Item from modal action', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Item'));

    livewire(ManageItems::class)
        ->callAction('create', data: $this->form_data)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('items', $this->form_data);
});

test('edits Item from modal action', function (): void {
    $item = Item::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Item'));

    livewire(ManageItems::class)
        ->callAction('edit', $item->getKey(), $this->form_data)
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('items', array_merge(['id' => $item->id], $this->form_data));
});

test('form validation requires fields on create and edit modal actions', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Item'));

    livewire(ManageItems::class)
        ->callAction('create', data: [
            'name' => '',
            'campaign_id' => null,
            'price' => null,
        ])
        ->assertHasTableActionErrors([
            'name' => 'required',
            'campaign_id' => 'required',
            'price' => 'required',
        ]);

    $item = Item::factory()->create();

    livewire(ManageItems::class)
        ->callAction('edit', $item->getKey(), [
            'name' => '',
            'campaign_id' => null,
            'price' => null,
        ])
        ->assertHasTableActionErrors([
            'name' => 'required',
            'campaign_id' => 'required',
            'price' => 'required',
        ]);
});

test('accepts price with multiple decimal places on create modal action', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Item'));

    livewire(ManageItems::class)
        ->callAction('create', data: array_merge($this->form_data, [
            'price' => 49.999999,
        ]))
        ->assertHasNoTableActionErrors();
});

test('accepts exact high precision price input', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Item'));

    $expectedPrice = '655.7377049180328';

    livewire(ManageItems::class)
        ->callAction('create', data: array_merge($this->form_data, [
            'price' => $expectedPrice,
        ]))
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas('items', [
        'name' => $this->form_data['name'],
        'campaign_id' => $this->form_data['campaign_id'],
    ]);
});

it('opens create, view and edit item modals from list page', function (): void {
    $item = Item::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['create', 'view', 'update', 'view-any'], 'Item'));

    livewire(ManageItems::class)
        ->mountTableAction('create')
        ->assertOk()
        ->mountTableAction('view', $item->getKey())
        ->assertOk()
        ->mountTableAction('edit', $item->getKey())
        ->assertOk();
});
