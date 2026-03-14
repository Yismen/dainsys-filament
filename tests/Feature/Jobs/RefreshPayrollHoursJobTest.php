<?php

use App\Jobs\RefreshPayrollHoursJob;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\PayrollHour;
use App\Models\Production;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;

describe('RefreshPayrollHoursJob', function (): void {
    beforeEach(function (): void {
        Bus::fake();
    });

    it('aggregates productions for a date and creates payroll hours', function (): void {
        $employee = Employee::factory()->create();
        $campaign1 = Campaign::factory()->create();
        $campaign2 = Campaign::factory()->create();
        $date = Carbon::parse('2025-01-15');

        Production::withoutEvents(function () use ($employee, $campaign1, $campaign2, $date): void {
            Production::factory()
                ->for($employee)
                ->for($campaign1)
                ->create([
                    'date' => $date,
                    'total_time' => 8.5,
                ]);

            Production::factory()
                ->for($employee)
                ->for($campaign2)
                ->create([
                    'date' => $date,
                    'total_time' => 2.5,
                ]);
        });

        (new RefreshPayrollHoursJob($date->toDateString()))->handle();

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $date->toDateString(),
            'total_hours' => 11.0,
        ]);
    });

    it('aggregates productions across the entire week for a given date', function (): void {
        $employee = Employee::factory()->create();
        $campaign = Campaign::factory()->create();
        $wednesday = Carbon::parse('2025-01-15'); // Wednesday
        $monday = $wednesday->clone()->startOfWeek();
        $friday = $wednesday->clone()->addDays(2);

        Production::withoutEvents(function () use ($employee, $campaign, $monday, $wednesday, $friday): void {
            Production::factory()
                ->for($employee)
                ->for($campaign)
                ->create(['date' => $monday, 'total_time' => 8]);

            Production::factory()
                ->for($employee)
                ->for($campaign)
                ->create(['date' => $wednesday, 'total_time' => 8]);

            Production::factory()
                ->for($employee)
                ->for($campaign)
                ->create(['date' => $friday, 'total_time' => 8]);
        });

        (new RefreshPayrollHoursJob($wednesday->toDateString()))->handle();

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $monday->toDateString(),
            'total_hours' => 8,
        ]);

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $wednesday->toDateString(),
            'total_hours' => 8,
        ]);

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $friday->toDateString(),
            'total_hours' => 8,
        ]);
    });

    it('refreshes payroll hours only for specified employee when employeeId is provided', function (): void {
        $employee1 = Employee::factory()->create();
        $employee2 = Employee::factory()->create();
        $campaign1 = Campaign::factory()->create();
        $campaign2 = Campaign::factory()->create();
        $date = Carbon::parse('2025-01-15');

        // Create productions without triggering model events (to avoid auto-sync)
        Production::withoutEvents(function () use ($employee1, $campaign1, $date, $employee2, $campaign2): void {
            Production::factory()
                ->for($employee1)
                ->for($campaign1)
                ->create(['date' => $date, 'total_time' => 8]);

            Production::factory()
                ->for($employee2)
                ->for($campaign2)
                ->create(['date' => $date, 'total_time' => 10]);
        });

        (new RefreshPayrollHoursJob($date->toDateString(), $employee1->id))->handle();

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee1->id,
            'date' => $date->toDateString(),
            'total_hours' => 8,
        ]);

        $this->assertDatabaseMissing('payroll_hours', [
            'employee_id' => $employee2->id,
            'date' => $date->toDateString(),
        ]);
    });

    it('excludes soft deleted productions from aggregation', function (): void {
        $employee = Employee::factory()->create();
        $campaign1 = Campaign::factory()->create();
        $campaign2 = Campaign::factory()->create();
        $date = Carbon::parse('2025-01-15');

        Production::withoutEvents(function () use ($employee, $campaign1, $campaign2, $date): void {
            $production1 = Production::factory()
                ->for($employee)
                ->for($campaign1)
                ->create(['date' => $date, 'total_time' => 8]);

            $production2 = Production::factory()
                ->for($employee)
                ->for($campaign2)
                ->create(['date' => $date, 'total_time' => 5]);

            $production1->delete();
        });

        (new RefreshPayrollHoursJob($date->toDateString()))->handle();

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $date->toDateString(),
            'total_hours' => 5,
        ]);
    });

    it('updates existing payroll hours with new total', function (): void {
        $employee = Employee::factory()->create();
        $campaign = Campaign::factory()->create();
        $date = Carbon::parse('2025-01-15');

        PayrollHour::factory()
            ->for($employee)
            ->create(['date' => $date, 'total_hours' => 10]);

        Production::withoutEvents(function () use ($employee, $campaign, $date): void {
            Production::factory()
                ->for($employee)
                ->for($campaign)
                ->create(['date' => $date, 'total_time' => 8]);
        });

        (new RefreshPayrollHoursJob($date->toDateString()))->handle();

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $date->toDateString(),
            'total_hours' => 8,
        ]);
    });

    it('moves holiday totals to holiday hours during refresh', function (): void {
        $employee = Employee::factory()->create();
        $campaign = Campaign::factory()->create();
        $date = Carbon::parse('2026-01-14');

        Holiday::factory()->create(['date' => $date]);

        Production::withoutEvents(function () use ($employee, $campaign, $date): void {
            Production::factory()
                ->for($employee)
                ->for($campaign)
                ->create(['date' => $date, 'total_time' => 9]);
        });

        (new RefreshPayrollHoursJob($date->toDateString()))->handle();

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $date->toDateString(),
            'total_hours' => 9,
            'holiday_hours' => 9,
            'regular_hours' => 0,
            'seventh_day_hours' => 0,
            'overtime_hours' => 0,
        ]);
    });

    it('assigns sunday as seventh day and spills overtime across latest regular dates', function (): void {
        $employee = Employee::factory()->create();
        $campaign = Campaign::factory()->create();
        $sunday = Carbon::parse('2026-01-18');
        $monday = $sunday->clone()->startOfWeek();
        $saturday = $sunday->clone()->subDay();

        Production::withoutEvents(function () use ($employee, $campaign, $monday): void {
            foreach (range(0, 6) as $offset) {
                Production::factory()
                    ->for($employee)
                    ->for($campaign)
                    ->create([
                        'date' => $monday->clone()->addDays($offset),
                        'total_time' => 10,
                    ]);
            }
        });

        (new RefreshPayrollHoursJob($sunday->toDateString()))->handle();

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $sunday->toDateString(),
            'seventh_day_hours' => 10,
            'regular_hours' => 0,
            'overtime_hours' => 0,
        ]);

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $saturday->toDateString(),
            'regular_hours' => 0,
            'overtime_hours' => 10,
            'seventh_day_hours' => 0,
        ]);

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $saturday->clone()->subDay()->toDateString(),
            'regular_hours' => 4,
            'overtime_hours' => 6,
        ]);

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $monday->toDateString(),
            'regular_hours' => 10,
            'overtime_hours' => 0,
        ]);
    });
});
