<?php

use App\Jobs\RefreshPayrollHoursJob;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Production;
use Illuminate\Support\Carbon;

it('moves all hours to holiday hours when date is a holiday', function (): void {
    $employee = Employee::factory()->create();
    $campaign = Campaign::factory()->create();
    $date = Carbon::parse('2026-01-15');

    Holiday::factory()->create(['date' => $date]);

    Production::withoutEvents(function () use ($employee, $campaign, $date): void {
        Production::factory()
            ->for($employee)
            ->for($campaign)
            ->create([
                'date' => $date,
                'total_time' => 8,
            ]);
    });

    (new RefreshPayrollHoursJob($date->toDateString(), $employee->id))->handle();

    $this->assertDatabaseHas('payroll_hours', [
        'employee_id' => $employee->id,
        'date' => $date->toDateString(),
        'holiday_hours' => 8,
        'regular_hours' => 0,
        'seventh_day_hours' => 0,
        'overtime_hours' => 0,
    ]);
});

it('assigns seventh day hours when employee worked all seven days and the date is sunday', function (): void {
    $employee = Employee::factory()->create();
    $campaign = Campaign::factory()->create();
    $sunday = Carbon::parse('2026-01-18');
    $monday = $sunday->clone()->startOfWeek();

    Production::withoutEvents(function () use ($employee, $campaign, $monday): void {
        foreach (range(0, 6) as $offset) {
            Production::factory()
                ->for($employee)
                ->for($campaign)
                ->create([
                    'date' => $monday->clone()->addDays($offset),
                    'total_time' => 6,
                ]);
        }
    });

    (new RefreshPayrollHoursJob($sunday->toDateString(), $employee->id))->handle();

    $this->assertDatabaseHas('payroll_hours', [
        'employee_id' => $employee->id,
        'date' => $sunday->toDateString(),
        'seventh_day_hours' => 6,
        'regular_hours' => 0,
        'holiday_hours' => 0,
        'overtime_hours' => 0,
    ]);

    $this->assertDatabaseHas('payroll_hours', [
        'employee_id' => $employee->id,
        'date' => $monday->toDateString(),
        'regular_hours' => 6,
        'holiday_hours' => 0,
        'seventh_day_hours' => 0,
        'overtime_hours' => 0,
    ]);
});

it('moves weekly excess regular hours into overtime hours', function (): void {
    $employee = Employee::factory()->create();
    $campaign = Campaign::factory()->create();
    $friday = Carbon::parse('2026-01-16');
    $monday = $friday->clone()->startOfWeek();

    Production::withoutEvents(function () use ($employee, $campaign, $monday): void {
        foreach (range(0, 4) as $offset) {
            Production::factory()
                ->for($employee)
                ->for($campaign)
                ->create([
                    'date' => $monday->clone()->addDays($offset),
                    'total_time' => 10,
                ]);
        }
    });

    (new RefreshPayrollHoursJob($friday->toDateString(), $employee->id))->handle();

    $this->assertDatabaseHas('payroll_hours', [
        'employee_id' => $employee->id,
        'date' => $friday->toDateString(),
        'regular_hours' => 4,
        'overtime_hours' => 6,
        'holiday_hours' => 0,
        'seventh_day_hours' => 0,
    ]);

    $this->assertDatabaseHas('payroll_hours', [
        'employee_id' => $employee->id,
        'date' => $monday->toDateString(),
        'regular_hours' => 10,
        'overtime_hours' => 0,
    ]);
});
