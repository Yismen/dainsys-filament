<?php

use App\Filament\Workforce\Resources\Sources\Pages\CreateSource;
use App\Filament\Workforce\Resources\Sources\Pages\EditSource;
use App\Filament\Workforce\Resources\Sources\Pages\ListSources;
use App\Filament\Workforce\Resources\Sources\Pages\ViewSource;
use App\Models\Source;
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

    $source = Source::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListSources::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        // 'create' => [
        //     'route' => CreateSource::getRouteName(),
        //     'params' => [],
        //     'permission' => ['create', 'view-any'],
        // ],
        // 'edit' => [
        //     'route' => EditSource::getRouteName(),
        //     'params' => ['record' => $source->getKey()],
        //     'permission' => ['update', 'edit', 'view-any'],
        // ],
        // 'view' => [
        //     'route' => ViewSource::getRouteName(),
        //     'params' => ['record' => $source->getKey()],
        //     'permission' => ['view', 'view-any'],
        // ],
    ];

    $this->form_data = [
        'name' => 'new Source',
        'description' => 'new Description',
    ];
});

it('require users to be authenticated to access Source resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.workforce.auth.login'));
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('require users to have correct permissions to access Source resource pages', function (string $method) {
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

it('allows super admin users to access Source resource pages', function (string $method) {
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

it('allow users with correct permissions to access Source resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Source'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('displays Source list page correctly', function () {
    Source::factory()->create();
    $sources = Source::get();

    actingAs($this->createUserWithPermissionTo('view-any Source'));

    livewire(ListSources::class)
        ->assertCanSeeTableRecords($sources);
});

test('table shows desired fields', function ($field) {
    $source = Source::factory()->create();

    actingAs($this->createUserWithPermissionTo('view-any Source'));

    livewire(ListSources::class)
        ->assertSee($source->$field);

})->with([
    'name',
    'description',
]);

test('create Source page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Source'));

    livewire(CreateSource::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('sources', $this->form_data);
});

test('edit Source page works correctly', function () {
    $source = Source::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Source'));

    livewire(EditSource::class, ['record' => $source->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('sources', array_merge(['id' => $source->id], $this->form_data));
});

test('form validation require fields on create and edit pages', function (string $field) {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Source'));

    // Test CreateSource validation
    livewire(CreateSource::class)
        ->fillForm([$field => ''])
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
    // Test EditSource validation
    $source = Source::factory()->create();
    livewire(EditSource::class, ['record' => $source->getKey()])
        ->fillForm([$field => ''])
        ->call('save')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'name',
]);

test('Source fields must be unique on create and edit pages', function (string $field) {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Source'));

    $existingSource = Source::factory()->create(['name' => 'Unique Source']);

    // Test CreateSource uniqueness validation
    livewire(CreateSource::class)
        ->fillForm([
            $field => 'Unique Source', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors([$field => 'unique']);
    // Test EditSource uniqueness validation
    $sourceToEdit = Source::factory()->create([$field => 'Another Source']);
    livewire(EditSource::class, ['record' => $sourceToEdit->getKey()])
        ->fillForm([
            $field => 'Unique Source', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors([$field => 'unique']);
})->with([
    'name',
]);

it('allows updating Source without changing field to trigger uniqueness validation', function (string $field) {
    $source = Source::factory()->create([$field => 'Existing Source']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Source'));

    livewire(EditSource::class, ['record' => $source->getKey()])
        ->fillForm([
            $field => 'Existing Source', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('sources', [
        'id' => $source->id,
        $field => 'Existing Source',
    ]);
})->with([
    'name',
]);

it('autofocus the employee_id field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Source'));

    // Test CreateSource autofocus
    livewire(CreateSource::class)
        ->assertSeeHtml('autofocus');

    // Test EditSource autofocus
    $source = Source::factory()->create();
    livewire(EditSource::class, ['record' => $source->getKey()])
        ->assertSeeHtml('autofocus');
});
