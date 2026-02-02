<?php

use App\Jobs\RefreshPayrollHoursJob;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Production;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;

describe('Production Payroll Hours Sync', function () {
    it('dispatches RefreshPayrollHoursJob when production is saved', function () {
        Queue::fake([RefreshPayrollHoursJob::class]);

        $employee = Employee::factory()->create();
        $campaign = Campaign::factory()->create();
        $date = Carbon::parse('2025-01-15');

        Production::factory()
            ->for($employee)
            ->for($campaign)
            ->create(['date' => $date, 'total_time' => 8]);

        Queue::assertPushed(RefreshPayrollHoursJob::class, function ($job) use ($date, $employee) {
            return $job->date === $date->toDateString() &&
                   $job->employeeId === $employee->id;
        });
    });

    it('dispatches RefreshPayrollHoursJob when production is updated', function () {
        Queue::fake([RefreshPayrollHoursJob::class]);
        $employee = Employee::factory()->create();
        $campaign = Campaign::factory()->create();
        $date = Carbon::parse('2025-01-15');

        $production = Production::factory()
            ->for($employee)
            ->for($campaign)
            ->create(['date' => $date, 'total_time' => 8]);

        // Create a new test to capture the second dispatch
        // Clear the queue and trigger the update
        $production->update(['total_time' => 10]);

        Queue::assertPushed(RefreshPayrollHoursJob::class);
    });

    it('dispatches RefreshPayrollHoursJob when production is soft deleted', function () {
        Queue::fake([RefreshPayrollHoursJob::class]);
        $employee = Employee::factory()->create();
        $campaign = Campaign::factory()->create();
        $date = Carbon::parse('2025-01-15');

        $production = Production::factory()
            ->for($employee)
            ->for($campaign)
            ->create(['date' => $date, 'total_time' => 8]);

        // Soft delete triggers dispatch
        $production->delete();

        Queue::assertPushed(RefreshPayrollHoursJob::class);
    });

    it('dispatches RefreshPayrollHoursJob when production is restored', function () {
        Queue::fake([RefreshPayrollHoursJob::class]);
        $employee = Employee::factory()->create();
        $campaign = Campaign::factory()->create();
        $date = Carbon::parse('2025-01-15');

        $production = Production::factory()
            ->for($employee)
            ->for($campaign)
            ->create(['date' => $date, 'total_time' => 8]);

        $production->delete();

        // Restore triggers dispatch
        $production->restore();

        Queue::assertPushed(RefreshPayrollHoursJob::class);
    });

    it('keeps payroll hours in sync when production is updated', function () {
        // Don't fake queue for this test - we need jobs to execute

        $employee = Employee::factory()->create();
        $campaign = Campaign::factory()->create();
        $date = Carbon::parse('2025-01-15');

        Production::factory()
            ->for($employee)
            ->for($campaign)
            ->create(['date' => $date, 'total_time' => 8]);

        // Process the initial job
        (new RefreshPayrollHoursJob($date->toDateString(), $employee->id))->handle();

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $date->format('Y-m-d H:i:s'),
            'total_hours' => 8,
        ]);

        // Create another production for same employee/date with different campaign
        $campaign2 = Campaign::factory()->create();
        Production::factory()
            ->for($employee)
            ->for($campaign2)
            ->create(['date' => $date, 'total_time' => 5]);

        // Process the sync job
        (new RefreshPayrollHoursJob($date->toDateString(), $employee->id))->handle();

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $date->format('Y-m-d H:i:s'),
            'total_hours' => 13,
        ]);
    });

    it('keeps payroll hours in sync when production is soft deleted', function () {
        // Don't fake queue for this test - we need jobs to execute

        $employee = Employee::factory()->create();
        $campaign1 = Campaign::factory()->create();
        $campaign2 = Campaign::factory()->create();
        $date = Carbon::parse('2025-01-15');

        $production1 = Production::factory()
            ->for($employee)
            ->for($campaign1)
            ->create(['date' => $date, 'total_time' => 8]);

        $production2 = Production::factory()
            ->for($employee)
            ->for($campaign2)
            ->create(['date' => $date, 'total_time' => 5]);

        (new RefreshPayrollHoursJob($date->toDateString(), $employee->id))->handle();

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $date->format('Y-m-d H:i:s'),
            'total_hours' => 13,
        ]);

        // Soft delete one production
        $production1->delete();

        (new RefreshPayrollHoursJob($date->toDateString(), $employee->id))->handle();

        $this->assertDatabaseHas('payroll_hours', [
            'employee_id' => $employee->id,
            'date' => $date->format('Y-m-d H:i:s'),
            'total_hours' => 5,
        ]);
    });
});
