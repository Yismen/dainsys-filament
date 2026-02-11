<?php

use App\Filament\Workforce\Resources\DowntimeReasons\Pages\CreateDowntimeReason;
use App\Filament\Workforce\Resources\DowntimeReasons\Pages\EditDowntimeReason;
use App\Filament\Workforce\Resources\DowntimeReasons\Pages\ListDowntimeReasons;
use App\Filament\Workforce\Resources\DowntimeReasons\Pages\ViewDowntimeReason;
use App\Models\DowntimeReason;
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

    $downtime_reason = DowntimeReason::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListDowntimeReasons::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        // 'create' => [
        //     'route' => CreateDowntimeReason::getRouteName(),
        //     'params' => [],
        //     'permission' => ['create', 'view-any'],
        // ],
        // 'edit' => [
        //     'route' => EditDowntimeReason::getRouteName(),
        //     'params' => ['record' => $downtime_reason->getKey()],
        //     'permission' => ['update', 'edit', 'view-any'],
        // ],
        // 'view' => [
        //     'route' => ViewDowntimeReason::getRouteName(),
        //     'params' => ['record' => $downtime_reason->getKey()],
        //     'permission' => ['view', 'view-any'],
        // ],
    ];

    $this->form_data = [
        'name' => 'new DowntimeReason',
        'description' => 'new Description',
    ];
});

it('require users to be authenticated to access DowntimeReason resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.workforce.auth.login'));
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('require users to have correct permissions to access DowntimeReason resource pages', function (string $method) {
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

it('allows super admin users to access DowntimeReason resource pages', function (string $method) {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('allow users with correct permissions to access DowntimeReason resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'DowntimeReason'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('displays DowntimeReason list page correctly', function () {
    DowntimeReason::factory()->create();
    $downtime_reasons = DowntimeReason::get();

    actingAs($this->createUserWithPermissionTo('view-any DowntimeReason'));

    livewire(ListDowntimeReasons::class)
        ->assertCanSeeTableRecords($downtime_reasons);
});

test('table shows desired fields', function ($field) {
    $downtime_reason = DowntimeReason::factory()->create();

    actingAs($this->createUserWithPermissionTo('view-any DowntimeReason'));

    livewire(ListDowntimeReasons::class)
        ->assertSee($downtime_reason->$field);

})->with([
    'name',
    'description',
]);

test('create DowntimeReason page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'DowntimeReason'));

    livewire(CreateDowntimeReason::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('downtime_reasons', $this->form_data);
});

test('edit DowntimeReason page works correctly', function () {
    $downtime_reason = DowntimeReason::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'DowntimeReason'));

    livewire(EditDowntimeReason::class, ['record' => $downtime_reason->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('downtime_reasons', array_merge(['id' => $downtime_reason->id], $this->form_data));
});

test('form validation require fields on create and edit pages', function (string $field) {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'DowntimeReason'));

    // Test CreateDowntimeReason validation
    livewire(CreateDowntimeReason::class)
        ->fillForm([$field => ''])
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
    // Test EditDowntimeReason validation
    $downtime_reason = DowntimeReason::factory()->create();
    livewire(EditDowntimeReason::class, ['record' => $downtime_reason->getKey()])
        ->fillForm([$field => ''])
        ->call('save')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'name',
]);

test('DowntimeReason fields must be unique on create and edit pages', function (string $field) {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'DowntimeReason'));

    $existingDowntimeReason = DowntimeReason::factory()->create(['name' => 'Unique DowntimeReason']);

    // Test CreateDowntimeReason uniqueness validation
    livewire(CreateDowntimeReason::class)
        ->fillForm([
            $field => 'Unique DowntimeReason', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors([$field => 'unique']);
    // Test EditDowntimeReason uniqueness validation
    $downtime_reasonToEdit = DowntimeReason::factory()->create([$field => 'Another DowntimeReason']);
    livewire(EditDowntimeReason::class, ['record' => $downtime_reasonToEdit->getKey()])
        ->fillForm([
            $field => 'Unique DowntimeReason', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors([$field => 'unique']);
})->with([
    'name',
]);

it('allows updating DowntimeReason without changing field to trigger uniqueness validation', function (string $field) {
    $downtime_reason = DowntimeReason::factory()->create([$field => 'Existing DowntimeReason']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'DowntimeReason'));

    livewire(EditDowntimeReason::class, ['record' => $downtime_reason->getKey()])
        ->fillForm([
            $field => 'Existing DowntimeReason', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('downtime_reasons', [
        'id' => $downtime_reason->id,
        $field => 'Existing DowntimeReason',
    ]);
})->with([
    'name',
]);

it('autofocus the employee_id field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'DowntimeReason'));

    // Test CreateDowntimeReason autofocus
    livewire(CreateDowntimeReason::class)
        ->assertSeeHtml('autofocus');

    // Test EditDowntimeReason autofocus
    $downtime_reason = DowntimeReason::factory()->create();
    livewire(EditDowntimeReason::class, ['record' => $downtime_reason->getKey()])
        ->assertSeeHtml('autofocus');
});
