<?php

use App\Filament\Workforce\Resources\Productions\Pages\CreateProduction;
use App\Filament\Workforce\Resources\Productions\Pages\EditProduction;
use App\Filament\Workforce\Resources\Productions\Pages\ListProductions;
use App\Filament\Workforce\Resources\Productions\Pages\ViewProduction;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Production;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('workforce'), // Where `app` is the ID of the panel you want to test.
    );

    $production = Production::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListProductions::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        // 'create' => [
        //     'route' => CreateProduction::getRouteName(),
        //     'params' => [],
        //     'permission' => ['create', 'view-any'],
        // ],
        'edit' => [
            'route' => EditProduction::getRouteName(),
            'params' => ['record' => $production->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewProduction::getRouteName(),
            'params' => ['record' => $production->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];

    $this->form_data = [
        'date' => now(),
        'employee_id' => Employee::factory()->create()->id,
        'campaign_id' => Campaign::factory()->create()->id,
        'conversions' => 5,
        'total_time' => 8,
        'production_time' => 7.5,
        'talk_time' => 7,
    ];
});

it('require users to be authenticated to access Production resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.workforce.auth.login'));
})->with([
    'index',
    // 'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access Production resource pages', function (string $method) {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));
    $response->assertForbidden();
})->with([
    'index',
    // 'create',
    'edit',
    'view',
]);

it('allows super admin users to access Production resource pages', function (string $method) {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    // 'create',
    'edit',
    'view',
]);

it('allow users with correct permissions to access Production resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Production'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    // 'create',
    'edit',
    'view',
]);

it('displays Production list page correctly', function () {
    Production::factory()->create();
    $productions = Production::get();

    actingAs($this->createUserWithPermissionTo('view-any Production'));

    livewire(ListProductions::class)
        ->assertCanSeeTableRecords($productions);
});

test('table shows desired fields', function ($field) {
    $production = Production::factory()->create();

    actingAs($this->createUserWithPermissionTo('view-any Production'));

    livewire(ListProductions::class)
        ->assertSee($production->$field);

})->with([
    // 'date',
    // 'employee_id',
    // 'campaign_id' ,
    'conversions',
    'total_time',
    'production_time',
    'talk_time',
]);

test('edit Production page works correctly', function () {
    $production = Production::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Production'));

    livewire(EditProduction::class, ['record' => $production->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $data = $this->form_data;

    unset($data['date']);

    $this->assertDatabaseHas('productions', array_merge(['id' => $production->id], $data));
});

test('form validation require fields on create and edit pages', function (string $field) {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Production'));

    // Test EditProduction validation
    $production = Production::factory()->create();
    livewire(EditProduction::class, ['record' => $production->getKey()])
        ->fillForm([$field => ''])
        ->call('save')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'date',
    'employee_id',
    'campaign_id',
    'conversions',
    'total_time',
    'production_time',
    'talk_time',
]);

// test('fields must be unique on create and edit pages', function (string $field) {
//     actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Production'));

//     $existingProduction = Production::factory()->create(['production' => 'Unique Production']);

//     // Test CreateProduction uniqueness validation
//     livewire(CreateProduction::class)
//         ->fillForm([
//             $field => 'Unique Production', // Invalid: name must be unique
//         ])
//         ->call('create')
//         ->assertHasFormErrors([$field => 'unique']);
//     // Test EditProduction uniqueness validation
//     $productionToEdit = Production::factory()->create([$field => 'Another Production']);
//     livewire(EditProduction::class, ['record' => $productionToEdit->getKey()])
//         ->fillForm([
//             $field => 'Unique Production', // Invalid: name must be unique
//         ])
//         ->call('save')
//         ->assertHasFormErrors([$field => 'unique']);
// })->with([
//     'production'
// ]);

// it('allows updating Production without changing field to trigger uniqueness validation', function (string $field) {
//     $production = Production::factory()->create([$field => 'Existing Production']);

//     actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Production'));

//     livewire(EditProduction::class, ['record' => $production->getKey()])
//         ->fillForm([
//             $field => 'Existing Production', // Same name, should not trigger uniqueness error
//         ])
//         ->call('save')
//         ->assertHasNoErrors();

//     $this->assertDatabaseHas('productions', [
//         'id' => $production->id,
//         $field => 'Existing Production',
//     ]);
// })->with([
//     'production',
// ]);

// it('autofocus the employee_id field on create and edit pages', function () {
//     actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Production'));

//     // Test EditProduction autofocus
//     $production = Production::factory()->create();
//     livewire(EditProduction::class, ['record' => $production->getKey()])
//         ->assertSeeHtml('autofocus');
// });
