<?php

use App\Enums\EmployeeStatuses;
use App\Filament\Supervisor\Resources\PayrollHours\PayrollHourResource;
use App\Models\Employee;
use App\Models\PayrollHour;
use App\Models\Supervisor;
use App\Models\User;
use Livewire\Livewire;

test('supervisor can view payroll hours for their employees', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    PayrollHour::factory()
        ->for($employee)
        ->count(5)
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\PayrollHours\Pages\ListPayrollHours::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords(PayrollHour::all());
});

test('supervisor cannot see terminated employees payroll hours', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $hiredEmployee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $terminatedEmployee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Terminated])
        ->create();

    $hiredHours = PayrollHour::factory()
        ->for($hiredEmployee)
        ->count(3)
        ->create();

    $terminatedHours = PayrollHour::factory()
        ->for($terminatedEmployee)
        ->count(2)
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\PayrollHours\Pages\ListPayrollHours::class)
        ->assertCanSeeTableRecords($hiredHours)
        ->assertCanNotSeeTableRecords($terminatedHours);
});

test('supervisor cannot see other supervisors employees payroll hours', function () {
    $supervisor1 = Supervisor::factory()->create();
    $supervisor2 = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor1)->create();

    $employee1 = Employee::factory()
        ->for($supervisor1)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $employee2 = Employee::factory()
        ->for($supervisor2)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $hours1 = PayrollHour::factory()->for($employee1)->create();
    $hours2 = PayrollHour::factory()->for($employee2)->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\PayrollHours\Pages\ListPayrollHours::class)
        ->assertCanSeeTableRecords([$hours1])
        ->assertCanNotSeeTableRecords([$hours2]);
});

test('supervisor can filter payroll hours by date range', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $oldHours = PayrollHour::factory()
        ->for($employee)
        ->state(['date' => now()->subMonth()])
        ->create();

    $recentHours = PayrollHour::factory()
        ->for($employee)
        ->state(['date' => now()])
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\PayrollHours\Pages\ListPayrollHours::class)
        ->filterTable('date', [
            'date_from' => now()->subWeek()->format('Y-m-d'),
            'date_until' => now()->format('Y-m-d'),
        ])
        ->assertCanSeeTableRecords([$recentHours])
        ->assertCanNotSeeTableRecords([$oldHours]);
});

test('supervisor can filter payroll hours by employee', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee1 = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $employee2 = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $hours1 = PayrollHour::factory()->for($employee1)->create();
    $hours2 = PayrollHour::factory()->for($employee2)->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\PayrollHours\Pages\ListPayrollHours::class)
        ->filterTable('employee_id', value: $employee1->id)
        ->assertCanSeeTableRecords([$hours1])
        ->assertCanNotSeeTableRecords([$hours2]);
});

test('payroll hours table displays correct columns', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    PayrollHour::factory()
        ->for($employee)
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\PayrollHours\Pages\ListPayrollHours::class)
        ->assertCanSeeTableColumns([
            'employee.full_name',
            'date',
            'regular_hours',
            'overtime_hours',
            'holiday_hours',
            'seventh_day_hours',
            'total_hours',
            'week_ending_at',
        ]);
});
