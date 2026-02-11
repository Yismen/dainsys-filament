<?php

use App\Enums\RevenueTypes;
use App\Filament\Workforce\Resources\Downtimes\Pages\CreateDowntime;
use App\Filament\Workforce\Resources\Downtimes\Pages\EditDowntime;
use App\Filament\Workforce\Resources\Downtimes\Pages\ListDowntimes;
use App\Filament\Workforce\Resources\Downtimes\Pages\ViewDowntime;
use App\Models\Campaign;
use App\Models\Downtime;
use App\Models\Employee;
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

    $downtime = Downtime::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListDowntimes::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        // 'create' => [
        //     'route' => CreateDowntime::getRouteName(),
        //     'params' => [],
        //     'permission' => ['create', 'view-any'],
        // ],
        // 'edit' => [
        //     'route' => EditDowntime::getRouteName(),
        //     'params' => ['record' => $downtime->getKey()],
        //     'permission' => ['update', 'edit', 'view-any'],
        // ],
        // 'view' => [
        //     'route' => ViewDowntime::getRouteName(),
        //     'params' => ['record' => $downtime->getKey()],
        //     'permission' => ['view', 'view-any'],
        // ],
    ];

    $this->form_data = [
        'date' => now(),
        'employee_id' => Employee::factory()->create()->id,
        'campaign_id' => Campaign::factory()->create(['revenue_type' => RevenueTypes::Downtime])->id,
        'total_time' => 5,
    ];
});

it('require users to be authenticated to access Downtime resource pages', function (string $method): void {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.workforce.auth.login'));
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('require users to have correct permissions to access Downtime resource pages', function (string $method): void {
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

it('allows super admin users to access Downtime resource pages', function (string $method): void {
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

it('allow users with correct permissions to access Downtime resource pages', function (string $method): void {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Downtime'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('displays Downtime list page correctly', function (): void {
    Downtime::factory()->create();
    $downtimes = Downtime::get();

    actingAs($this->createUserWithPermissionTo('view-any Downtime'));

    livewire(ListDowntimes::class)
        ->assertCanSeeTableRecords($downtimes);
});

test('table shows desired fields', function ($field): void {
    $downtime = Downtime::factory()->create();

    actingAs($this->createUserWithPermissionTo('view-any Downtime'));

    livewire(ListDowntimes::class)
        ->assertSee($downtime->$field);

})->with([
    // 'date',
    // 'employee_id',
    // 'campaign_id',
    // 'downtime_reason_id',
    'total_time',
    // 'requester_id',
    // 'aprover_id',
    // 'converted_to_payroll_at',
]);

test('edit Downtime page works correctly', function (): void {
    $downtime = Downtime::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Downtime'));

    livewire(EditDowntime::class, ['record' => $downtime->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $data = $this->form_data;

    unset($data['date']);

    $this->assertDatabaseHas('downtimes', array_merge(['id' => $downtime->id], $data));
});

test('form validation require fields on create and edit pages', function (string $field): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Downtime'));

    // Test EditDowntime validation
    $downtime = Downtime::factory()->create();
    livewire(EditDowntime::class, ['record' => $downtime->getKey()])
        ->fillForm([$field => ''])
        ->call('save')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'date',
    'employee_id',
    'campaign_id',
    'downtime_reason_id',
    'total_time',
    // 'requester_id',
    // 'aprover_id',
    // 'converted_to_payroll_at',
]);

// test('fields must be unique on create and edit pages', function (string $field) {
//     actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Downtime'));

//     $existingDowntime = Downtime::factory()->create(['downtime' => 'Unique Downtime']);

//     // Test CreateDowntime uniqueness validation
//     livewire(CreateDowntime::class)
//         ->fillForm([
//             $field => 'Unique Downtime', // Invalid: name must be unique
//         ])
//         ->call('create')
//         ->assertHasFormErrors([$field => 'unique']);
//     // Test EditDowntime uniqueness validation
//     $downtimeToEdit = Downtime::factory()->create([$field => 'Another Downtime']);
//     livewire(EditDowntime::class, ['record' => $downtimeToEdit->getKey()])
//         ->fillForm([
//             $field => 'Unique Downtime', // Invalid: name must be unique
//         ])
//         ->call('save')
//         ->assertHasFormErrors([$field => 'unique']);
// })->with([
//         'employee_id',
//         'campaign_id',
//         'downtime_reason_id',
//         'total_time',
//         'requester_id',
//         'aprover_id',
//         'converted_to_payroll_at',
// ]);

// it('allows updating Downtime without changing field to trigger uniqueness validation', function (string $field) {
//     $downtime = Downtime::factory()->create([$field => 'Existing Downtime']);

//     actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Downtime'));

//     livewire(EditDowntime::class, ['record' => $downtime->getKey()])
//         ->fillForm([
//             $field => 'Existing Downtime', // Same name, should not trigger uniqueness error
//         ])
//         ->call('save')
//         ->assertHasNoErrors();

//     $this->assertDatabaseHas('downtimes', [
//         'id' => $downtime->id,
//         $field => 'Existing Downtime',
//     ]);
// })->with([
//     'downtime',
// ]);

// it('autofocus the employee_id field on create and edit pages', function () {
//     actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Downtime'));

//     // Test EditDowntime autofocus
//     $downtime = Downtime::factory()->create();
//     livewire(EditDowntime::class, ['record' => $downtime->getKey()])
//         ->assertSeeHtml('autofocus');
// });
