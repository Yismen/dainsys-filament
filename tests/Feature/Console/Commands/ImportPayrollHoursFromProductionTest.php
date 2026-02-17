<?php

use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeSuspendedEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Jobs\RefreshPayrollHoursJob;
use App\Models\Employee;
use App\Models\Production;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

// beforeEach(function () {
//     Event::fake([
//         EmployeeHiredEvent::class,
//         EmployeeSuspendedEvent::class,
//         EmployeeTerminatedEvent::class,
//     ]);
// });

it('runs correctly', function (): void {
    Queue::fake([RefreshPayrollHoursJob::class]);

    $this->artisan('dainsys:import-payroll-hours-from-production', ['date' => now()])
        ->assertSuccessful();
});

it('dispatches RefreshPayrollHoursJob with the correct date argument', function (): void {
    Queue::fake([RefreshPayrollHoursJob::class]);

    $date = '2025-01-15';

    $this->artisan('dainsys:import-payroll-hours-from-production', ['date' => $date])
        ->expectsOutput("Dispatched payroll hours refresh for {$date}")
        ->assertSuccessful();

    Queue::assertPushed(RefreshPayrollHoursJob::class, function ($job) use ($date) {
        return $job->date === $date;
    });
});

it('summarize all data for downtimes and production for the week', function (): void {
    Bus::fake();

    $employee = Employee::factory()->create();
    Production::withoutEvents(function () use ($employee): void {
        Production::factory()->for($employee)->create(['date' => '2026-01-10', 'total_time' => 7]);
        Production::factory()->for($employee)->create(['date' => '2026-01-10', 'total_time' => 7]);
        Production::factory()->for($employee)->create(['date' => '2026-01-07', 'total_time' => 7]);
        Production::factory()->create(['date' => '2026-01-07', 'total_time' => 7]);
    });

    (new RefreshPayrollHoursJob('2026-01-10'))->handle();

    $this->assertDatabaseHas('payroll_hours', [
        'date' => '2026-01-10 00:00:00',
        'employee_id' => $employee->id,
        'total_hours' => 14,
    ]);

    $this->assertDatabaseHas('payroll_hours', [
        'date' => '2026-01-07 00:00:00',
        'employee_id' => $employee->id,
        'total_hours' => 7,
    ]);
});

it('sumarize data based on dates for the same week', function (): void {
    Bus::fake();

    Production::withoutEvents(function (): void {
        Production::factory()->create(['date' => '2026-01-10', 'total_time' => 7]);
        Production::factory()->create(['date' => '2026-01-09', 'total_time' => 7]);
    });

    (new RefreshPayrollHoursJob('2026-01-11'))->handle();

    $this->assertDatabaseHas('payroll_hours', [
        'date' => '2026-01-09 00:00:00',
        'total_hours' => 7,
    ]);

    $this->assertDatabaseHas('payroll_hours', [
        'date' => '2026-01-10 00:00:00',
        'total_hours' => 7,
    ]);
});

it('summarize data based on employees for the same week', function (): void {
    Bus::fake();

    $employee_one = Employee::factory()->create();
    $employee_two = Employee::factory()->create();
    Production::withoutEvents(function () use ($employee_one, $employee_two): void {
        Production::factory()->for($employee_one)->create(['date' => '2026-01-10', 'total_time' => 10]);
        Production::factory()->for($employee_two)->create(['date' => '2026-01-10', 'total_time' => 7]);
    });

    (new RefreshPayrollHoursJob('2026-01-10'))->handle();

    $this->assertDatabaseHas('payroll_hours', [
        'date' => '2026-01-10 00:00:00',
        'employee_id' => $employee_one->id,
        'total_hours' => 10,
    ]);

    $this->assertDatabaseHas('payroll_hours', [
        'date' => '2026-01-10 00:00:00',
        'employee_id' => $employee_two->id,
        'total_hours' => 7,
    ]);
});

it('summarize data based on the week and ignores other weeks', function (): void {
    Bus::fake();

    $employee = Employee::factory()->create();
    Production::withoutEvents(function () use ($employee): void {
        Production::factory()->for($employee)->create(['date' => '2026-01-01', 'total_time' => 10]);
        Production::factory()->for($employee)->create(['date' => '2026-01-10', 'total_time' => 7]);
    });

    (new RefreshPayrollHoursJob('2026-01-11'))->handle();

    $this->assertDatabaseHas('payroll_hours', [
        'date' => '2026-01-10 00:00:00',
        'employee_id' => $employee->id,
        'total_hours' => 7,
    ]);
});

it('is schedulled to run for the previous day and every hour at the 23 minute', function (): void {
    $command = collect(
        app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events()
        )
        ->first(function ($element) {
            return str($element->command)->contains('dainsys:import-payroll-hours-from-production date="'.now()->subDay()->format('Y-m-d').'"');
        });

    expect($command->expression)->toBe('23 * * * *');
});
