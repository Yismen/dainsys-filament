<?php

use App\Events\EmployeeHiredEvent;
use App\Events\SocialSecurityUpdatedEvent;
use App\Filament\HumanResource\Resources\SocialSecurities\Pages\CreateSocialSecurity;
use App\Filament\HumanResource\Resources\SocialSecurities\Pages\EditSocialSecurity;
use App\Filament\HumanResource\Resources\SocialSecurities\Pages\ListSocialSecurities;
use App\Filament\HumanResource\Resources\SocialSecurities\Pages\ViewSocialSecurity;
use App\Models\Afp;
use App\Models\Ars;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\SocialSecurity;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    // Seed roles/permissions if applicable
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'), // Where `app` is the ID of the panel you want to test.
    );
    Event::fake([
        EmployeeHiredEvent::class,
        SocialSecurityUpdatedEvent::class,
    ]);

    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $social_security = SocialSecurity::factory()->for($employee)->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListSocialSecurities::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        'create' => [
            'route' => CreateSocialSecurity::getRouteName(),
            'params' => [],
            'permission' => ['create', 'view-any'],
        ],
        'edit' => [
            'route' => EditSocialSecurity::getRouteName(),
            'params' => ['record' => $social_security->getKey()],
            'permission' => ['update', 'edit', 'view-any'],
        ],
        'view' => [
            'route' => ViewSocialSecurity::getRouteName(),
            'params' => ['record' => $social_security->getKey()],
            'permission' => ['view', 'view-any'],
        ],
    ];

    $other_employee = Employee::factory()->create();
    Hire::factory()->for($other_employee)->create();

    $this->form_data = [
        'employee_id' => $other_employee->id,
        'ars_id' => Ars::factory()->create()->id,
        'afp_id' => Afp::factory()->create()->id,
        'number' => '454545',
    ];
});

it('require users to be authenticated to access SocialSecurity resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.human-resource.auth.login'));
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('require users to have correct permissions to access SocialSecurity resource pages', function (string $method) {
    actingAs(User::factory()->create());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));
    $response->assertForbidden();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('allows super admin users to access SocialSecurity resource pages', function (string $method) {
    actingAs($this->createSuperAdminUser());

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('allow users with correct permissions to access SocialSecurity resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'SocialSecurity'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    'create',
    'edit',
    'view',
]);

it('displays SocialSecurity list page correctly', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    SocialSecurity::factory()->for($employee)->create();
    $social_securities = SocialSecurity::get();

    actingAs($this->createUserWithPermissionTo('view-any SocialSecurity'));

    livewire(ListSocialSecurities::class)
        ->assertCanSeeTableRecords($social_securities);
});

test('create SocialSecurity page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'SocialSecurity'));

    livewire(CreateSocialSecurity::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('social_securities', $this->form_data);
});

test('edit SocialSecurity page works correctly', function () {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $social_security = SocialSecurity::factory()->for($employee)->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'SocialSecurity'));

    livewire(EditSocialSecurity::class, ['record' => $social_security->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('social_securities', array_merge(['id' => $social_security->id], $this->form_data));
});

test('form validation require fields on create and edit pages', function (string $field) {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'SocialSecurity'));

    // Test CreateSocialSecurity validation
    livewire(CreateSocialSecurity::class)
        ->fillForm([$field => ''])
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
    // Test EditSocialSecurity validation
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $social_security = SocialSecurity::factory()->for($employee)->create();
    livewire(EditSocialSecurity::class, ['record' => $social_security->getKey()])
        ->fillForm([$field => ''])
        ->call('save')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'employee_id',
    'ars_id',
    'afp_id',
    'number',
]);

// it('autofocus the employee_id field on create and edit pages', function () {
//     actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'SocialSecurity'));

//     // Test CreateSocialSecurity autofocus
//     livewire(CreateSocialSecurity::class)
//         ->assertSeeHtml('autofocus');

//     // Test EditSocialSecurity autofocus
//     $social_security = SocialSecurity::factory()->create();
//     livewire(EditSocialSecurity::class, ['record' => $social_security->getKey()])
//         ->assertSeeHtml('autofocus');
// });
