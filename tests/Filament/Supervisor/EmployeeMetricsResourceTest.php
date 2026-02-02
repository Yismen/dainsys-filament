<?php

use App\Enums\EmployeeStatuses;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\PayrollHour;
use App\Models\Production;
use App\Models\Supervisor;
use App\Models\User;
use Livewire\Livewire;

test('supervisor can view employee metrics for their employees', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\EmployeeMetrics\Pages\ListEmployeeMetrics::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$employee]);
});

test('supervisor cannot see terminated employees in metrics', function () {
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

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\EmployeeMetrics\Pages\ListEmployeeMetrics::class)
        ->assertCanSeeTableRecords([$hiredEmployee])
        ->assertCanNotSeeTableRecords([$terminatedEmployee]);
});

test('employee metrics calculates total conversions correctly', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $campaign = Campaign::factory()->create();

    Production::factory()
        ->for($employee)
        ->for($campaign)
        ->for($supervisor)
        ->state(['conversions' => 10])
        ->create();

    Production::factory()
        ->for($employee)
        ->for($campaign)
        ->for($supervisor)
        ->state(['conversions' => 15])
        ->create();

    $response = Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\EmployeeMetrics\Pages\ListEmployeeMetrics::class);

    $response->assertSuccessful();
});

test('employee metrics calculates sph correctly', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $campaign = Campaign::factory()->create();

    // 10 conversions, 3600 seconds production time = 10 SPH
    Production::factory()
        ->for($employee)
        ->for($campaign)
        ->for($supervisor)
        ->state(['conversions' => 10, 'production_time' => 3600])
        ->create();

    $response = Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\EmployeeMetrics\Pages\ListEmployeeMetrics::class);

    $response->assertSuccessful();
});

test('employee metrics calculates efficiency rate correctly', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    // 8 regular hours, 10 total hours = 80% efficiency
    PayrollHour::factory()
        ->for($employee)
        ->state(['regular_hours' => 8, 'total_hours' => 10])
        ->create();

    $response = Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\EmployeeMetrics\Pages\ListEmployeeMetrics::class);

    $response->assertSuccessful();
});

test('employee metrics can filter by date range', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\EmployeeMetrics\Pages\ListEmployeeMetrics::class)
        ->filterTable('date', [
            'date_from' => now()->subMonth()->format('Y-m-d'),
            'date_until' => now()->format('Y-m-d'),
        ])
        ->assertSuccessful();
});

test('metrics table displays correct columns', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\EmployeeMetrics\Pages\ListEmployeeMetrics::class)
        ->assertCanSeeTableColumns([
            'full_name',
            'total_conversions',
            'sph',
            'sph_percentage',
            'efficiency_rate',
        ]);
});
