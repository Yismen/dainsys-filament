<?php

use App\Console\Commands\ImportPayrollHoursFromProduction;
use App\Events\EmployeeHiredEvent;
use App\Events\SuspensionUpdatedEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Models\Employee;
use App\Models\Production;
use Illuminate\Support\Facades\Event;

// beforeEach(function () {
//     Event::fake([
//         EmployeeHiredEvent::class,
//         SuspensionUpdatedEvent::class,
//         EmployeeTerminatedEvent::class,
//     ]);
// });

it('runs correctly', function () {
    $this->artisan('dainsys:import-payroll-hours-from-production', ['date' => now()])
        ->assertSuccessful();
});

it('summarize all data for downtimes and production for the week', function () {
    $employee = Employee::factory()->create();
    Production::factory()->for($employee)->create(['date' => '2026-01-10', 'total_time' => 7]);
    Production::factory()->for($employee)->create(['date' => '2026-01-10', 'total_time' => 7]);
    Production::factory()->for($employee)->create(['date' => '2026-01-07', 'total_time' => 7]);
    Production::factory()->create(['date' => '2026-01-07', 'total_time' => 7]);

    $this->artisan(ImportPayrollHoursFromProduction::class, ['date' => '2026-01-10']);

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

it('sumarize data based on dates for the same week', function () {
    Production::factory()->create(['date' => '2026-01-10', 'total_time' => 7]);
    Production::factory()->create(['date' => '2026-01-09', 'total_time' => 7]);

    $this->artisan(ImportPayrollHoursFromProduction::class, ['date' => '2026-01-11']);

    $this->assertDatabaseHas('payroll_hours', [
        'date' => '2026-01-09 00:00:00',
        'total_hours' => 7,
    ]);

    $this->assertDatabaseHas('payroll_hours', [
        'date' => '2026-01-10 00:00:00',
        'total_hours' => 7,
    ]);
});

it('summarize data based on employees for the same week', function () {
    $employee_one = Employee::factory()->create();
    $employee_two = Employee::factory()->create();
    Production::factory()->for($employee_one)->create(['date' => '2026-01-10', 'total_time' => 10]);
    Production::factory()->for($employee_two)->create(['date' => '2026-01-10', 'total_time' => 7]);

    $this->artisan(ImportPayrollHoursFromProduction::class, ['date' => '2026-01-10']);

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

it('summarize data based on the week and ignores other weeks', function () {
    $employee = Employee::factory()->create();
    Production::factory()->for($employee)->create(['date' => '2026-01-01', 'total_time' => 10]);
    Production::factory()->for($employee)->create(['date' => '2026-01-10', 'total_time' => 7]);

    $this->artisan(ImportPayrollHoursFromProduction::class, ['date' => '2026-01-11']);

    $this->assertDatabaseHas('payroll_hours', [
        'date' => '2026-01-10 00:00:00',
        'employee_id' => $employee->id,
        'total_hours' => 7,
    ]);
});

it('is schedulled to run every hour at the 23 minute', function () {
    $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->filter(function ($element) {
            return str($element->command)->contains('dainsys:import-payroll-hours-from-production');
        })->first();

    expect($addedToScheduler)->not->toBeNull();
    expect($addedToScheduler->expression)->toEqual('23 * * * *');
});

it('is schedulled to run for the previous day', function () {
    $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->filter(function ($element) {
            return str($element->command)->contains('dainsys:import-payroll-hours-from-production date="'.now()->subDay()->format('Y-m-d'));
        })->first();

    expect($addedToScheduler)->not->toBeNull();
});
