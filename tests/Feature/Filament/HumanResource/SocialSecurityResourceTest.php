<?php

use App\Events\EmployeeHiredEvent;
use App\Events\SocialSecurityUpdatedEvent;
use App\Filament\HumanResource\Resources\SocialSecurities\Pages\ManageSocialSecurities;
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

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('human-resource'),
    );

    $this->indexRoute = ManageSocialSecurities::getRouteName();

    Event::fake([
        EmployeeHiredEvent::class,
        SocialSecurityUpdatedEvent::class,
    ]);

    $otherEmployee = Employee::factory()->create();
    Hire::factory()->for($otherEmployee)->create();

    $this->form_data = [
        'employee_id' => $otherEmployee->id,
        'ars_id' => Ars::factory()->create()->id,
        'afp_id' => Afp::factory()->create()->id,
        'number' => '454545',
    ];
});

it('requires users to be authenticated to access the SocialSecurity resource', function (): void {
    $response = get(route($this->indexRoute));
    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the SocialSecurity resource', function (): void {
    actingAs(User::factory()->create());
    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the SocialSecurity resource', function (): void {
    actingAs($this->createSuperAdminUser());
    $response = get(route($this->indexRoute));
    $response->assertOk();
});

it('allows users with correct permissions to access the SocialSecurity resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['view-any'], 'SocialSecurity'));
    $response = get(route($this->indexRoute));
    $response->assertOk();
});

it('displays SocialSecurity list page correctly', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    SocialSecurity::factory()->for($employee)->create();
    $social_securities = SocialSecurity::get();

    actingAs($this->createUserWithPermissionTo('view-any SocialSecurity'));

    livewire(ManageSocialSecurities::class)
        ->assertCanSeeTableRecords($social_securities);
});

test('can filter SocialSecurities by ars', function (): void {
    $arsA = Ars::factory()->create();
    $arsB = Ars::factory()->create();

    $arsAEmployee = Employee::factory()->create();
    Hire::factory()->for($arsAEmployee)->create();

    $arsBEmployee = Employee::factory()->create();
    Hire::factory()->for($arsBEmployee)->create();

    $arsASocialSecurity = SocialSecurity::factory()->for($arsAEmployee)->for($arsA)->create();
    $arsBSocialSecurity = SocialSecurity::factory()->for($arsBEmployee)->for($arsB)->create();

    actingAs($this->createUserWithPermissionTo('view-any SocialSecurity'));

    livewire(ManageSocialSecurities::class)
        ->filterTable('ars_id', (string) $arsA->id)
        ->assertCanSeeTableRecords([$arsASocialSecurity])
        ->assertCanNotSeeTableRecords([$arsBSocialSecurity]);
});

test('can filter SocialSecurities by afp', function (): void {
    $afpA = Afp::factory()->create();
    $afpB = Afp::factory()->create();

    $afpAEmployee = Employee::factory()->create();
    Hire::factory()->for($afpAEmployee)->create();

    $afpBEmployee = Employee::factory()->create();
    Hire::factory()->for($afpBEmployee)->create();

    $afpASocialSecurity = SocialSecurity::factory()->for($afpAEmployee)->for($afpA)->create();
    $afpBSocialSecurity = SocialSecurity::factory()->for($afpBEmployee)->for($afpB)->create();

    actingAs($this->createUserWithPermissionTo('view-any SocialSecurity'));

    livewire(ManageSocialSecurities::class)
        ->filterTable('afp_id', (string) $afpA->id)
        ->assertCanSeeTableRecords([$afpASocialSecurity])
        ->assertCanNotSeeTableRecords([$afpBSocialSecurity]);
});

test('create SocialSecurity via modal works correctly', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'view-any'], 'SocialSecurity'));

    livewire(ManageSocialSecurities::class)
        ->callAction('create', $this->form_data);

    $this->assertDatabaseHas('social_securities', $this->form_data);
});

test('edit SocialSecurity via modal works correctly', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $social_security = SocialSecurity::factory()->for($employee)->create();

    actingAs($this->createUserWithPermissionsToActions(['update', 'view-any'], 'SocialSecurity'));

    livewire(ManageSocialSecurities::class)
        ->callTableAction('edit', $social_security, $this->form_data)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('social_securities', array_merge(['id' => $social_security->id], $this->form_data));
});

test('form validation requires fields on create and edit modals', function (string $field): void {
    actingAs($this->createUserWithPermissionsToActions(['create', 'update', 'view-any'], 'SocialSecurity'));

    livewire(ManageSocialSecurities::class)
        ->callAction('create', [$field => ''])
        ->assertHasFormErrors([$field => 'required']);

    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create();
    $social_security = SocialSecurity::factory()->for($employee)->create();

    livewire(ManageSocialSecurities::class)
        ->callTableAction('edit', $social_security, [$field => ''])
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'employee_id',
    'ars_id',
    'afp_id',
]);
