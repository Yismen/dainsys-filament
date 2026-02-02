<?php

use App\Jobs\DistributePayrollHoursJob;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\PayrollHour;
use Illuminate\Support\Carbon;

it('moves all hours to holiday hours when date is a holiday', function () {
    $employee = Employee::factory()->create();
    $date = Carbon::parse('2026-01-15');

    Holiday::factory()->create(['date' => $date]);

    PayrollHour::factory()
        ->for($employee)
        ->create([
            'date' => $date,
            'total_hours' => 8,
            'regular_hours' => 0,
            'holiday_hours' => 0,
            'seventh_day_hours' => 0,
            'overtime_hours' => 0,
        ]);

    (new DistributePayrollHoursJob($date->toDateString(), $employee->id))->handle();

    $this->assertDatabaseHas('payroll_hours', [
        'employee_id' => $employee->id,
        'date' => $date->format('Y-m-d H:i:s'),
        'holiday_hours' => 8,
        'regular_hours' => 0,
        'seventh_day_hours' => 0,
        'overtime_hours' => 0,
    ]);
});

it('assigns seventh day hours when employee worked all seven days and the date is sunday', function () {
    $employee = Employee::factory()->create();
    $sunday = Carbon::parse('2026-01-18');
    $monday = $sunday->clone()->startOfWeek();

    foreach (range(0, 6) as $offset) {
        PayrollHour::factory()
            ->for($employee)
            ->create([
                'date' => $monday->clone()->addDays($offset),
                'total_hours' => 6,
                'regular_hours' => 0,
                'holiday_hours' => 0,
                'seventh_day_hours' => 0,
                'overtime_hours' => 0,
            ]);
    }

    (new DistributePayrollHoursJob($sunday->toDateString(), $employee->id))->handle();

    $this->assertDatabaseHas('payroll_hours', [
        'employee_id' => $employee->id,
        'date' => $sunday->format('Y-m-d H:i:s'),
        'seventh_day_hours' => 6,
        'regular_hours' => 0,
        'holiday_hours' => 0,
        'overtime_hours' => 0,
    ]);

    $this->assertDatabaseHas('payroll_hours', [
        'employee_id' => $employee->id,
        'date' => $monday->format('Y-m-d H:i:s'),
        'regular_hours' => 6,
        'holiday_hours' => 0,
        'seventh_day_hours' => 0,
        'overtime_hours' => 0,
    ]);
});

it('moves weekly excess regular hours into overtime hours', function () {
    $employee = Employee::factory()->create();
    $friday = Carbon::parse('2026-01-16');
    $monday = $friday->clone()->startOfWeek();

    foreach (range(0, 4) as $offset) {
        PayrollHour::factory()
            ->for($employee)
            ->create([
                'date' => $monday->clone()->addDays($offset),
                'total_hours' => 10,
                'regular_hours' => 0,
                'holiday_hours' => 0,
                'seventh_day_hours' => 0,
                'overtime_hours' => 0,
            ]);
    }

    (new DistributePayrollHoursJob($friday->toDateString(), $employee->id))->handle();

    $this->assertDatabaseHas('payroll_hours', [
        'employee_id' => $employee->id,
        'date' => $friday->format('Y-m-d H:i:s'),
        'regular_hours' => 4,
        'overtime_hours' => 6,
        'holiday_hours' => 0,
        'seventh_day_hours' => 0,
    ]);

    $this->assertDatabaseHas('payroll_hours', [
        'employee_id' => $employee->id,
        'date' => $monday->format('Y-m-d H:i:s'),
        'regular_hours' => 10,
        'overtime_hours' => 0,
    ]);
});
