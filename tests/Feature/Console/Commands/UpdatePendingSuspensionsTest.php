<?php

use App\Console\Commands\UpdatePendingSuspensions;
use App\Enums\EmployeeStatuses;
use App\Enums\SuspensionStatuses;
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

test('command is schedulled to run every hour at the 15 minute', function (): void {
    $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->first(function ($element) {
            return str($element->command)->contains('dainsys:update-pending-suspensions');
        });

    expect($addedToScheduler->expression)->toBe('*/15 * * * *');
});

test('It ignores suspensions in status Completed', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDays(10)]);
    $suspension = Suspension::factory()->create([
        'employee_id' => $employee->id,
        'starts_at' => now()->subDays(5),
        'ends_at' => now()->subDay(2),
    ]);

    expect($suspension->fresh()->status)
        ->toBe(SuspensionStatuses::Completed);

    $this->artisan(UpdatePendingSuspensions::class);

    expect($suspension->fresh()->status)
        ->toBe(SuspensionStatuses::Completed);
});

test('It change pasts suspensions to status Completed', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDays(10)]);
    $suspension = Suspension::factory()->create([
        'employee_id' => $employee->id,
        'starts_at' => now()->subDays(5),
        'ends_at' => now()->addDays(2),
    ]);

    expect($suspension->fresh()->status)
        ->toBe(SuspensionStatuses::Current);

    $this->travelTo(now()->addDays(5));

    $this->artisan(UpdatePendingSuspensions::class);

    expect($suspension->fresh()->status)
        ->toBe(SuspensionStatuses::Completed);
});

test('It change pending suspensions to status Current', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDays(10)]);
    $suspension = Suspension::factory()->create([
        'employee_id' => $employee->id,
        'starts_at' => now()->addDays(2),
        'ends_at' => now()->addDays(5),
    ]);

    expect($suspension->fresh()->status)
        ->toBe(SuspensionStatuses::Pending);

    $this->travelTo(now()->addDays(3));

    $this->artisan(UpdatePendingSuspensions::class);

    expect($suspension->fresh()->status)
        ->toBe(SuspensionStatuses::Current);
});

test('inactive employees are ignrored', function (): void {
    $employee = Employee::factory()->create();
    Hire::factory()->for($employee)->create(['date' => now()->subDays(10)]);
    $suspension = Suspension::factory()->create([
        'employee_id' => $employee->id,
        'starts_at' => now()->addDays(2),
        'ends_at' => now()->addDays(5),
    ]);

    Termination::factory()->for($employee)->create();

    expect($suspension->fresh()->status)
        ->toBe(SuspensionStatuses::Pending);
    expect($employee->fresh()->status)
        ->toBe(EmployeeStatuses::Terminated);

    $this->travelTo(now()->addDays(3));

    $this->artisan(UpdatePendingSuspensions::class);

    expect($suspension->fresh()->status)
        ->toBe(SuspensionStatuses::Pending);
    expect($employee->fresh()->status)
        ->toBe(EmployeeStatuses::Terminated);
});
