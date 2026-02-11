<?php

use App\Enums\RevenueTypes;
use App\Filament\Workforce\Resources\Campaigns\Pages\CreateCampaign;
use App\Filament\Workforce\Resources\Campaigns\Pages\EditCampaign;
use App\Filament\Workforce\Resources\Campaigns\Pages\ListCampaigns;
use App\Filament\Workforce\Resources\Campaigns\Pages\ViewCampaign;
use App\Models\Campaign;
use App\Models\Project;
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

    $campaign = Campaign::factory()->create();

    $this->resource_routes = [
        'index' => [
            'route' => ListCampaigns::getRouteName(),
            'params' => [],
            'permission' => ['view-any'],
        ],
        // 'create' => [
        //     'route' => CreateCampaign::getRouteName(),
        //     'params' => [],
        //     'permission' => ['create', 'view-any'],
        // ],
        // 'edit' => [
        //     'route' => EditCampaign::getRouteName(),
        //     'params' => ['record' => $campaign->getKey()],
        //     'permission' => ['update', 'edit', 'view-any'],
        // ],
        // 'view' => [
        //     'route' => ViewCampaign::getRouteName(),
        //     'params' => ['record' => $campaign->getKey()],
        //     'permission' => ['view', 'view-any'],
        // ],
    ];

    $this->form_data = [
        'name' => 'new Campaign',
        'project_id' => Project::factory()->create()->id,
        'source_id' => Source::factory()->create()->id,
        'revenue_type' => RevenueTypes::LoginTime,
        'sph_goal' => 2.35,
        'revenue_rate' => 3.75,
        'description' => 'Campaign description',
    ];
});

it('require users to be authenticated to access Campaign resource pages', function (string $method) {
    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertRedirect(route('filament.workforce.auth.login'));
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('require users to have correct permissions to access Campaign resource pages', function (string $method) {
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

it('allows super admin users to access Campaign resource pages', function (string $method) {
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

it('allow users with correct permissions to access Campaign resource pages', function (string $method) {
    actingAs($this->createUserWithPermissionsToActions($this->resource_routes[$method]['permission'], 'Campaign'));

    $response = get(route($this->resource_routes[$method]['route'],
        $this->resource_routes[$method]['params']));

    $response->assertOk();
})->with([
    'index',
    // 'create',
    // 'edit',
    // 'view',
]);

it('displays Campaign list page correctly', function () {
    Campaign::factory()->create();
    $campaigns = Campaign::get();

    actingAs($this->createUserWithPermissionTo('view-any Campaign'));

    livewire(ListCampaigns::class)
        ->assertCanSeeTableRecords($campaigns);
});

test('table shows desired fields', function ($field) {
    $campaign = Campaign::factory()->create();

    actingAs($this->createUserWithPermissionTo('view-any Campaign'));

    livewire(ListCampaigns::class)
        ->assertSee($campaign->$field);

})->with([
    'name',
    // 'project_id',
    // 'source_id',
    'revenue_type',
    'sph_goal',
    'revenue_rate',
    'description',
]);

test('create Campaign page works correctly', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'Campaign'));

    livewire(CreateCampaign::class)
        ->fillForm($this->form_data)
        ->call('create');

    $this->assertDatabaseHas('campaigns', $this->form_data);
});

test('edit Campaign page works correctly', function () {
    $campaign = Campaign::factory()->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Campaign'));

    livewire(EditCampaign::class, ['record' => $campaign->getKey()])
        ->fillForm($this->form_data)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('campaigns', array_merge(['id' => $campaign->id], $this->form_data));
});

test('form validation require fields on create and edit pages', function (string $field) {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Campaign'));

    // Test CreateCampaign validation
    livewire(CreateCampaign::class)
        ->fillForm([$field => ''])
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
    // Test EditCampaign validation
    $campaign = Campaign::factory()->create();
    livewire(EditCampaign::class, ['record' => $campaign->getKey()])
        ->fillForm([$field => ''])
        ->call('save')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'name',
    'project_id',
    'source_id',
    'revenue_type',
    'sph_goal',
    'revenue_rate',
    // 'description',
]);

test('fields must be unique on create and edit pages', function (string $field) {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Campaign'));

    $existingCampaign = Campaign::factory()->create(['name' => 'Unique Campaign']);

    // Test CreateCampaign uniqueness validation
    livewire(CreateCampaign::class)
        ->fillForm([
            $field => 'Unique Campaign', // Invalid: name must be unique
        ])
        ->call('create')
        ->assertHasFormErrors([$field => 'unique']);
    // Test EditCampaign uniqueness validation
    $campaignToEdit = Campaign::factory()->create([$field => 'Another Campaign']);
    livewire(EditCampaign::class, ['record' => $campaignToEdit->getKey()])
        ->fillForm([
            $field => 'Unique Campaign', // Invalid: name must be unique
        ])
        ->call('save')
        ->assertHasFormErrors([$field => 'unique']);
})->with([
    'name',
]);

it('allows updating Campaign without changing field to trigger uniqueness validation', function (string $field) {
    $campaign = Campaign::factory()->create([$field => 'Existing Campaign']);

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'Campaign'));

    livewire(EditCampaign::class, ['record' => $campaign->getKey()])
        ->fillForm([
            $field => 'Existing Campaign', // Same name, should not trigger uniqueness error
        ])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('campaigns', [
        'id' => $campaign->id,
        $field => 'Existing Campaign',
    ]);
})->with([
    'name',
]);

it('autofocus the employee_id field on create and edit pages', function () {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'Campaign'));

    // Test CreateCampaign autofocus
    livewire(CreateCampaign::class)
        ->assertSeeHtml('autofocus');

    // Test EditCampaign autofocus
    $campaign = Campaign::factory()->create();
    livewire(EditCampaign::class, ['record' => $campaign->getKey()])
        ->assertSeeHtml('autofocus');
});
