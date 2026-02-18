<?php

use App\Enums\RevenueTypes;
use App\Filament\Workforce\Resources\Dispositions\Pages\CreateDisposition;
use App\Filament\Workforce\Resources\Dispositions\Pages\EditDisposition;
use App\Filament\Workforce\Resources\Dispositions\Pages\ListDispositions;
use App\Filament\Workforce\Resources\Dispositions\Pages\ViewDisposition;
use App\Models\Disposition;
use App\Models\Project;
use App\Models\Source;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('workforce'), // Where `app` is the ID of the panel you want to test.
    );

    $disposition = Disposition::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListDispositions::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        // 'create' => [
        //     'route' => CreateDisposition::getRouteName(),
        //     'params' => [],
        //     'permission' => ['create', 'view-any'],
        // ],
        // 'edit' => [
        //     'route' => EditDisposition::getRouteName(),
        //     'params' => ['record' => $disposition->getKey()],
        //     'permission' => ['update', 'edit', 'view-any'],
        // ],
        // 'view' => [
        //     'route' => ViewDisposition::getRouteName(),
        //     'params' => ['record' => $disposition->getKey()],
        //     'permission' => ['view', 'view-any'],
        // ],
    ];

    $this->form_data = [
        'name' => 'new Disposition',
        'sales' => 2.35,
        'description' => 'Disposition description',
    ];
});

it('require users to be authenticated to access Disposition resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.workforce.auth.login'));
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('require users to have correct permissions to access Disposition resource pages', function (string $method): void {
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

it('allows super admin users to access Disposition resource pages', function (string $method): void {
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

it('allow users with correct permissions to access Disposition resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Disposition'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('displays Disposition list page correctly', function (): void {
    Disposition::factory()->create();
    $dispositions = Disposition::get();

    actingAs($this->createUserWithPermissionTo('view-any Disposition'));

    livewire(ListDispositions::class)
        ->assertCanSeeTableRecords($dispositions);
});

test('table shows desired fields', function ($field): void {
    $disposition = Disposition::factory()->create();

    actingAs($this->createUserWithPermissionTo('view-any Disposition'));

    livewire(ListDispositions::class)
        ->assertSee($disposition->$field);

})->with([
    'name',
    // 'sales',
    'description',
]);

test('create Disposition page works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Disposition'));

    livewire(CreateDisposition::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('dispositions', $this->form_data);
});

test('edit Disposition page works correctly', function (): void {
    $disposition = Disposition::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Disposition'));

    livewire(EditDisposition::class, ['record' => $disposition->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('dispositions', array_merge(['id' => $disposition->id], $this->form_data));
});

test('form validation require fields on create and edit pages', function (string $field): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Disposition'));

    // Test CreateDisposition validation
    livewire(CreateDisposition::class)
        ->fillForm([$field => ''])
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
    // Test EditDisposition validation
    $disposition = Disposition::factory()->create();
    livewire(EditDisposition::class, ['record' => $disposition->getKey()])
        ->fillForm([$field => ''])
        ->call('save')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'name',
    // 'description',
]);

test('fields must be unique on create and edit pages', function (string $field): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Disposition'));

    $existingDisposition = Disposition::factory()->create(['name' => 'Unique Disposition']);

    // Test CreateDisposition uniqueness validation
    livewire(CreateDisposition::class)
        ->fillForm([
            $field => 'Unique Disposition', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors([$field => 'unique']);
    // Test EditDisposition uniqueness validation
    $dispositionToEdit = Disposition::factory()->create([$field => 'Another Disposition']);
    livewire(EditDisposition::class, ['record' => $dispositionToEdit->getKey()])
        ->fillForm([
            $field => 'Unique Disposition', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors([$field => 'unique']);
})->with([
    'name',
]);

it('allows updating Disposition without changing field to trigger uniqueness validation', function (string $field): void {
    $disposition = Disposition::factory()->create([$field => 'Existing Disposition']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Disposition'));

    livewire(EditDisposition::class, ['record' => $disposition->getKey()])
        ->fillForm([
            $field => 'Existing Disposition', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('dispositions', [
        'id' => $disposition->id,
        $field => 'Existing Disposition',
    ]);
})->with([
    'name',
]);

it('autofocus the employee_id field on create and edit pages', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Disposition'));

    // Test CreateDisposition autofocus
    livewire(CreateDisposition::class)
        ->assertSeeHtml('autofocus');

    // Test EditDisposition autofocus
    $disposition = Disposition::factory()->create();
    livewire(EditDisposition::class, ['record' => $disposition->getKey()])
        ->assertSeeHtml('autofocus');
});
