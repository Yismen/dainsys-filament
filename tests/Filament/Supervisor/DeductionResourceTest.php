<?php

use App\Enums\EmployeeStatuses;
use App\Filament\Supervisor\Resources\Deductions\Pages\ListDeductions;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Supervisor;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('supervisor'),
    );
});

test('supervisor can view deductions for their active employees', function (): void {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = $supervisor->user;

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    Deduction::factory()
        ->for($employee)
        ->count(5)
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(ListDeductions::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords(Deduction::all());
});

test('supervisor cannot see terminated employees deductions', function (): void {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = $supervisor->user;

    $hiredEmployee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $terminatedEmployee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Terminated])
        ->create();

    $hiredDeductions = Deduction::factory()
        ->for($hiredEmployee)
        ->count(3)
        ->create();

    $terminatedDeductions = Deduction::factory()
        ->for($terminatedEmployee)
        ->count(2)
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(ListDeductions::class)
        ->assertCanSeeTableRecords($hiredDeductions)
        ->assertCanNotSeeTableRecords($terminatedDeductions);
});

test('supervisor cannot see other supervisors employees deductions', function (): void {
    $supervisor1 = Supervisor::factory()->create();
    $supervisor2 = Supervisor::factory()->create();
    $supervisorUser = $supervisor1->user;

    $employee1 = Employee::factory()
        ->for($supervisor1)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $employee2 = Employee::factory()
        ->for($supervisor2)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $deduction1 = Deduction::factory()->for($employee1)->create();
    $deduction2 = Deduction::factory()->for($employee2)->create();

    Livewire::actingAs($supervisorUser)
        ->test(ListDeductions::class)
        ->assertCanSeeTableRecords([$deduction1])
        ->assertCanNotSeeTableRecords([$deduction2]);
});

test('supervisor can filter deductions by payable date range', function (): void {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = $supervisor->user;

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $oldDeduction = Deduction::factory()
        ->for($employee)
        ->state(['payable_date' => now()->subMonth()])
        ->create();

    $recentDeduction = Deduction::factory()
        ->for($employee)
        ->state(['payable_date' => now()])
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(ListDeductions::class)
        ->filterTable('payable_date', [
            'payable_date_from' => now()->subWeek()->format('Y-m-d'),
            'payable_date_until' => now()->format('Y-m-d'),
        ])
        ->assertCanSeeTableRecords([$recentDeduction])
        ->assertCanNotSeeTableRecords([$oldDeduction]);
});

test('supervisor can filter deductions by employee', function (): void {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = $supervisor->user;

    $employee1 = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $employee2 = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $deduction1 = Deduction::factory()->for($employee1)->create();
    $deduction2 = Deduction::factory()->for($employee2)->create();

    Livewire::actingAs($supervisorUser)
        ->test(ListDeductions::class)
        ->filterTable('employee_id', $employee1->id)
        ->assertCanSeeTableRecords([$deduction1])
        ->assertCanNotSeeTableRecords([$deduction2]);
});

test('deductions table displays correct columns', function (): void {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = $supervisor->user;

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    Deduction::factory()
        ->for($employee)
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(ListDeductions::class)
        ->assertCanSeeTableColumns([
            'employee.full_name',
            'payable_date',
            'amount',
            'description',
        ]);
});
