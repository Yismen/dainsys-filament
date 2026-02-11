<?php

use App\Filament\Workforce\Resources\LoginNames\Pages\CreateLoginName;
use App\Filament\Workforce\Resources\LoginNames\Pages\EditLoginName;
use App\Filament\Workforce\Resources\LoginNames\Pages\ListLoginNames;
use App\Filament\Workforce\Resources\LoginNames\Pages\ViewLoginName;
use App\Models\Employee;
use App\Models\LoginName;
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

    $login_name = LoginName::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListLoginNames::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        // 'create' => [
        //     'route' => CreateLoginName::getRouteName(),
        //     'params' => [],
        //     'permission' => ['create', 'view-any'],
        // ],
        // 'edit' => [
        //     'route' => EditLoginName::getRouteName(),
        //     'params' => ['record' => $login_name->getKey()],
        //     'permission' => ['update', 'edit', 'view-any'],
        // ],
        // 'view' => [
        //     'route' => ViewLoginName::getRouteName(),
        //     'params' => ['record' => $login_name->getKey()],
        //     'permission' => ['view', 'view-any'],
        // ],
    ];

    $this->form_data = [
        'login_name' => 'new LoginName',
        'employee_id' => Employee::factory()->create()->id,
    ];
});

it('require users to be authenticated to access LoginName resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.workforce.auth.login'));
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('require users to have correct permissions to access LoginName resource pages', function (string $method) {
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

it('allows super admin users to access LoginName resource pages', function (string $method) {
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

it('allow users with correct permissions to access LoginName resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'LoginName'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('displays LoginName list page correctly', function () {
    LoginName::factory()->create();
    $login_names = LoginName::get();

    actingAs($this->createUserWithPermissionTo('view-any LoginName'));

    livewire(ListLoginNames::class)
        ->assertCanSeeTableRecords($login_names);
});

test('table shows desired fields', function ($field) {
    $login_name = LoginName::factory()->create();

    actingAs($this->createUserWithPermissionTo('view-any LoginName'));

    livewire(ListLoginNames::class)
        ->assertSee($login_name->$field);

})->with([
    'login_name',
    // 'employee.full_name',
]);

test('create LoginName page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'LoginName'));

    livewire(CreateLoginName::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('login_names', $this->form_data);
});

test('edit LoginName page works correctly', function () {
    $login_name = LoginName::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'LoginName'));

    livewire(EditLoginName::class, ['record' => $login_name->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('login_names', array_merge(['id' => $login_name->id], $this->form_data));
});

test('form validation require fields on create and edit pages', function (string $field) {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'LoginName'));

    // Test CreateLoginName validation
    livewire(CreateLoginName::class)
        ->fillForm([$field => ''])
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
    // Test EditLoginName validation
    $login_name = LoginName::factory()->create();
    livewire(EditLoginName::class, ['record' => $login_name->getKey()])
        ->fillForm([$field => ''])
        ->call('save')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'login_name',
    'employee_id',
]);

test('fields must be unique on create and edit pages', function (string $field) {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'LoginName'));

    $existingLoginName = LoginName::factory()->create(['login_name' => 'Unique LoginName']);

    // Test CreateLoginName uniqueness validation
    livewire(CreateLoginName::class)
        ->fillForm([
            $field => 'Unique LoginName', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors([$field => 'unique']);
    // Test EditLoginName uniqueness validation
    $login_nameToEdit = LoginName::factory()->create([$field => 'Another LoginName']);
    livewire(EditLoginName::class, ['record' => $login_nameToEdit->getKey()])
        ->fillForm([
            $field => 'Unique LoginName', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors([$field => 'unique']);
})->with([
    'login_name',
]);

it('allows updating LoginName without changing field to trigger uniqueness validation', function (string $field) {
    $login_name = LoginName::factory()->create([$field => 'Existing LoginName']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'LoginName'));

    livewire(EditLoginName::class, ['record' => $login_name->getKey()])
        ->fillForm([
            $field => 'Existing LoginName', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('login_names', [
        'id' => $login_name->id,
        $field => 'Existing LoginName',
    ]);
})->with([
    'login_name',
]);

it('autofocus the employee_id field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'LoginName'));

    // Test CreateLoginName autofocus
    livewire(CreateLoginName::class)
        ->assertSeeHtml('autofocus');

    // Test EditLoginName autofocus
    $login_name = LoginName::factory()->create();
    livewire(EditLoginName::class, ['record' => $login_name->getKey()])
        ->assertSeeHtml('autofocus');
});
