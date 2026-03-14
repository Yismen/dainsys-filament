<?php

use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Filament\Supervisor\Resources\EmployeeMetrics\EmployeeMetricsResource;
use App\Filament\Supervisor\Resources\EmployeeMetrics\Pages\ListEmployeeMetrics;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Permission;
use App\Models\Production;
use App\Models\Supervisor;
use App\Models\Termination;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function (): void {
    Mail::fake();
    Event::fake([EmployeeHiredEvent::class, EmployeeTerminatedEvent::class]);

    Filament::setCurrentPanel(
        Filament::getPanel('supervisor'),
    );
});

test('employee metrics groups productions by week and employee', function (): void {
    /** @var User $supervisorUser */
    $supervisorUser = User::factory()->create();
    $permission = Permission::firstOrCreate(['name' => 'viewAny production']);
    $supervisorUser->givePermissionTo($permission);
    $supervisor = Supervisor::factory()->for($supervisorUser, 'user')->create();

    $employeeOne = Employee::factory()->create();
    Hire::factory()->for($employeeOne)->for($supervisor)->create();

    $employeeTwo = Employee::factory()->create();
    Hire::factory()->for($employeeTwo)->for($supervisor)->create();

    $campaign = Campaign::factory()->create();

    $currentWeek = now()->startOfWeek();
    $previousWeek = now()->copy()->subWeek()->startOfWeek();

    Production::factory()
        ->for($employeeOne)
        ->for($campaign)
        ->for($supervisor)
        ->state([
            'date' => $currentWeek->copy()->addDay(),
            'conversions' => 11,
        ])
        ->create();

    Production::factory()
        ->for($employeeOne)
        ->for($campaign)
        ->for($supervisor)
        ->state([
            'date' => $currentWeek->copy()->addDays(2),
            'conversions' => 13,
        ])
        ->create();

    Production::factory()
        ->for($employeeOne)
        ->for($campaign)
        ->for($supervisor)
        ->state([
            'date' => $previousWeek->copy()->addDay(),
            'conversions' => 7,
        ])
        ->create();

    Production::factory()
        ->for($employeeTwo)
        ->for($campaign)
        ->for($supervisor)
        ->state([
            'date' => $currentWeek->copy()->addDay(),
            'conversions' => 5,
        ])
        ->create();

    actingAs($supervisorUser);

    $records = EmployeeMetricsResource::getEloquentQuery()->get();

    expect($records)->toHaveCount(3);
    expect($records->pluck('total_conversions')->map(fn ($value) => (float) $value)->all())
        ->toEqualCanonicalizing([24.0, 7.0, 5.0]);
    expect($records->pluck('full_name')->all())
        ->toContain($employeeOne->full_name, $employeeTwo->full_name);
    expect($records->pluck('week_ending')->all())
        ->toContain(
            $currentWeek->copy()->endOfWeek()->format('Y-m-d'),
            $previousWeek->copy()->endOfWeek()->format('Y-m-d'),
        );
});

test('employee metrics table does not add productions id as a default sort', function (): void {
    /** @var User $supervisorUser */
    $supervisorUser = User::factory()->create();
    $permission = Permission::firstOrCreate(['name' => 'viewAny production']);
    $supervisorUser->givePermissionTo($permission);
    $supervisor = Supervisor::factory()->for($supervisorUser, 'user')->create();

    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->for($supervisor)->create();

    actingAs($supervisorUser);

    $component = Livewire::test(ListEmployeeMetrics::class)
        ->assertSuccessful();

    $sql = strtolower($component->instance()->getFilteredSortedTableQuery()->toSql());

    expect($sql)->toContain('order by');
    expect($sql)->not->toContain('order by productions.id');
    expect($sql)->not->toContain('order by "productions"."id"');
    expect($sql)->not->toContain(', productions.id');
    expect($sql)->not->toContain(', "productions"."id"');
});

test('supervisor cannot see terminated employees in metrics', function (): void {
    /** @var User $supervisorUser */
    $supervisorUser = User::factory()->create();
    $permission = Permission::firstOrCreate(['name' => 'viewAny production']);
    $supervisorUser->givePermissionTo($permission);
    $supervisor = Supervisor::factory()->for($supervisorUser, 'user')->create();

    $hiredEmployee = Employee::factory()->create();
    Hire::factory()->for($hiredEmployee)->for($supervisor)->create();

    $terminatedEmployee = Employee::factory()->create();
    Hire::factory()->for($terminatedEmployee)->for($supervisor)->create();
    Termination::factory()->for($terminatedEmployee)->create();

    $campaign = Campaign::factory()->create();

    Production::factory()
        ->for($hiredEmployee)
        ->for($campaign)
        ->for($supervisor)
        ->create();

    Production::factory()
        ->for($terminatedEmployee)
        ->for($campaign)
        ->for($supervisor)
        ->create();

    actingAs($supervisorUser);

    $records = EmployeeMetricsResource::getEloquentQuery()->get();

    expect($records)->toHaveCount(1);
    expect($records->first()->full_name)->toBe($hiredEmployee->full_name);
    expect($records->pluck('full_name')->all())->not->toContain($terminatedEmployee->full_name);
});

test('employee metrics route renders successfully for supervisor', function (): void {
    /** @var User $supervisorUser */
    $supervisorUser = User::factory()->create();
    $permission = Permission::firstOrCreate(['name' => 'viewAny production']);
    $supervisorUser->givePermissionTo($permission);
    $supervisor = Supervisor::factory()->for($supervisorUser, 'user')->create();

    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->for($supervisor)->create();
    $campaign = Campaign::factory()->create();

    Production::factory()
        ->for($employee)
        ->for($campaign)
        ->for($supervisor)
        ->state([
            'date' => now()->startOfWeek()->addDay(),
            'conversions' => 10,
            'conversions_goal' => 10,
            'total_time' => 5,
            'production_time' => 4,
            'billable_time' => 3,
        ])
        ->create();

    actingAs($supervisorUser);

    get(EmployeeMetricsResource::getUrl(panel: 'supervisor'))
        ->assertSuccessful()
        ->assertSee('Employee Metrics');
});
