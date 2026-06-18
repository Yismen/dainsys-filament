<?php

use App\Enums\HRActivityRequestStatuses;
use App\Enums\HRActivityTypes;
use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Filament\HumanResource\Resources\HRActivityRequests\Pages\ManageHRActivityRequests;
use App\Models\Employee;
use App\Models\HRActivityRequest;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use App\Models\Supervisor;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Mail::fake();
    Event::fake([
        EmployeeHiredEvent::class,
        EmployeeTerminatedEvent::class,
    ]);

    Filament::setCurrentPanel(
        Filament::getPanel('human-resource')
    );

    Role::firstOrCreate(['name' => 'Human Resource Manager'], ['guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'Human Resource Agent'], ['guard_name' => 'web']);

    Cache::flush();

    $this->indexRoute = ManageHRActivityRequests::getRouteName();
});

it('requires users to be authenticated to access the HRActivityRequest resource', function (): void {
    $response = get(route($this->indexRoute));
    $response->assertRedirect(route('filament.human-resource.auth.login'));
});

it('requires users to have correct permissions to access the HRActivityRequest resource', function (): void {
    $user = User::factory()->create();
    actingAs($user);
    $response = get(route($this->indexRoute));
    $response->assertForbidden();
});

it('allows super admin users to access the HRActivityRequest resource', function (): void {
    actingAs($this->createSuperAdminUser());
    $response = get(route($this->indexRoute));
    $response->assertOk();
});

it('allows users with correct permissions to access the HRActivityRequest resource', function (): void {
    actingAs($this->createUserWithPermissionsToActions(['viewAny'], 'HRActivityRequest'));
    $response = get(route($this->indexRoute));
    $response->assertOk();
});

test('hr can view list of activity requests', function (): void {
    $hrUser = $this->createUserWithPermissionsToActions(
        actions: ['viewAny'],
        model_name: 'HRActivityRequest'
    );
    actingAs($hrUser);

    $requests = HRActivityRequest::factory()->count(3)->create();

    livewire(ManageHRActivityRequests::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords($requests);
});

test('hr can filter requests by status', function (): void {
    $hrUser = $this->createUserWithPermissionsToActions(
        actions: ['viewAny'],
        model_name: 'HRActivityRequest'
    );
    actingAs($hrUser);

    $requestedRequest = HRActivityRequest::factory()->create([
        'status' => HRActivityRequestStatuses::Requested,
    ]);

    $completedRequest = HRActivityRequest::factory()->create([
        'status' => HRActivityRequestStatuses::Completed,
        'completed_at' => now(),
        'completion_comment' => 'Done',
    ]);

    livewire(ManageHRActivityRequests::class)
        ->filterTable('status', HRActivityRequestStatuses::Requested->value)
        ->assertCanSeeTableRecords([$requestedRequest])
        ->assertCanNotSeeTableRecords([$completedRequest]);
});

test('hr can complete a request with comment', function (): void {
    $hrUser = $this->createUserWithPermissionsToActions(
        actions: ['viewAny', 'complete'],
        model_name: 'HRActivityRequest'
    );
    actingAs($hrUser);

    $request = HRActivityRequest::factory()->create([
        'status' => HRActivityRequestStatuses::Requested,
    ]);

    livewire(ManageHRActivityRequests::class)
        ->callTableAction('complete', $request, [
            'comment' => 'Request completed successfully',
        ])
        ->assertNotified();

    $request->refresh();

    expect($request->status)->toBe(HRActivityRequestStatuses::Completed);
    expect($request->completion_comment)->toBe('Request completed successfully');
    expect($request->completed_at)->not->toBeNull();
});

test('create HRActivityRequest via modal works correctly', function (): void {
    $hrUser = $this->createUserWithPermissionsToActions(
        actions: ['create', 'viewAny'],
        model_name: 'HRActivityRequest'
    );
    actingAs($hrUser);

    $employee = Employee::factory()->create();
    $employee->status = \App\Enums\EmployeeStatuses::Hired;
    $employee->saveQuietly();
    $supervisor = Supervisor::factory()->create();

    livewire(ManageHRActivityRequests::class)
        ->callAction('create', [
            'employee_id' => $employee->id,
            'supervisor_id' => $supervisor->id,
            'activity_type' => HRActivityTypes::Vacations->value,
            'description' => 'Test request',
            'requested_at' => now()->format('Y-m-d H:i:s'),
        ])
        ->assertHasNoErrors();

    $this->assertDatabaseHas('h_r_activity_requests', [
        'employee_id' => $employee->id,
        'supervisor_id' => $supervisor->id,
        'activity_type' => HRActivityTypes::Vacations,
    ]);
});

test('form validation requires fields on create modal', function (string $field): void {
    $hrUser = $this->createUserWithPermissionsToActions(
        actions: ['create', 'viewAny'],
        model_name: 'HRActivityRequest'
    );
    actingAs($hrUser);

    livewire(ManageHRActivityRequests::class)
        ->callAction('create', [$field => ''])
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'employee_id',
    'supervisor_id',
    'activity_type',
    'requested_at',
]);
