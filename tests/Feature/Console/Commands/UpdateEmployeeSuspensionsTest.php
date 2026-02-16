<?php

use App\Console\Commands\UpdateEmployeeSuspensions;
use App\Enums\EmployeeStatuses;
use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeSuspendedEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Suspension;
use App\Models\Termination;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    Event::fake([
        EmployeeHiredEvent::class,
        EmployeeSuspendedEvent::class,
        EmployeeTerminatedEvent::class,
    ]);
});

test('install command creates site', function (): void {
    $this->artisan('dainsys:update-employee-suspensions')
        ->assertSuccessful();
});

test('command is schedulled for daily at 300 am', function (): void {
     $this->app->make(\Illuminate\Contracts\Console\Kernel::class);

    $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->filter(function ($element) {
            return str($element->command)->contains('dainsys:update-employee-suspensions');
        })->first();

    expect($addedToScheduler)->not->toBeNull();
    expect($addedToScheduler->expression)->toEqual('0 3 * * *');
});

test('current employees are suspended', function (): void {
    $current = Employee::factory()
        ->hasHires()
        ->create();
    Suspension::factory()->create([
        'employee_id' => $current->id,
        'starts_at' => now(),
        'ends_at' => now()->addDay(),
    ]);

    $this->artisan(UpdateEmployeeSuspensions::class);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatuses::Suspended,
    ]);
});

test('inactive employees are not suspended', function (): void {
    $current = Employee::factory()
        ->hasHires()
        ->create();

    Termination::factory()->for($current)->create();

    $this->artisan(UpdateEmployeeSuspensions::class);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatuses::Terminated,
    ]);
});

test('employee is not suspended if starts at is after now', function (): void {
    $current = Employee::factory()
        ->create();
    Hire::factory()->for($current)->create(['date' => now()->subDays(10)]);

    Suspension::factory()->create([
        'employee_id' => $current->id,
        'starts_at' => now()->addDay(),
        'ends_at' => now()->addDay(),
    ]);

    $this->artisan(UpdateEmployeeSuspensions::class);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatuses::Hired,
    ]);
});

test('employee is not suspended if ends at is before now', function (): void {
    $current = Employee::factory()->create();
    Hire::factory()->for($current)->create(['date' => now()->subDays(5)]);
    Suspension::factory()->create([
        'employee_id' => $current->id,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->subDay(),
    ]);

    $this->artisan(UpdateEmployeeSuspensions::class);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatuses::Hired,
    ]);
});

test('suspended employees are activated if today is prior to starts at', function (): void {
    $current = Employee::factory()->create();
    Hire::factory()->for($current)->create();
    Suspension::factory()->create([
        'employee_id' => $current->id,
        'starts_at' => now(),
        'ends_at' => now()->addDay(),
    ]);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatuses::Suspended,
    ]);

    $this->travelTo(now()->subDays(5));

    $this->artisan(UpdateEmployeeSuspensions::class);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatuses::Hired,
    ]);
});

test('suspended employees are activated if today is after ends at', function (): void {
    $current = Employee::factory()->create();
    Hire::factory()->for($current)->create();
    Suspension::factory()->create([
        'employee_id' => $current->id,
        'starts_at' => now(),
        'ends_at' => now()->addDay(),
    ]);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatuses::Suspended,
    ]);

    $this->travelTo(now()->addDays(5));

    $this->artisan(UpdateEmployeeSuspensions::class);

    $this->assertDatabaseHas('employees', [
        'id' => $current->id,
        'status' => EmployeeStatuses::Hired,
    ]);
});
