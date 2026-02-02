<?php

use App\Enums\EmployeeStatuses;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Production;
use App\Models\Supervisor;
use App\Models\User;
use Livewire\Livewire;

test('supervisor can view production data for their employees', function () {
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
        ->count(5)
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\Productions\Pages\ListProductions::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords(Production::all());
});

test('supervisor cannot see terminated employees production data', function () {
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

    $campaign = Campaign::factory()->create();

    $hiredProduction = Production::factory()
        ->for($hiredEmployee)
        ->for($campaign)
        ->for($supervisor)
        ->count(3)
        ->create();

    $terminatedProduction = Production::factory()
        ->for($terminatedEmployee)
        ->for($campaign)
        ->for($supervisor)
        ->count(2)
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\Productions\Pages\ListProductions::class)
        ->assertCanSeeTableRecords($hiredProduction)
        ->assertCanNotSeeTableRecords($terminatedProduction);
});

test('supervisor can filter production by date range', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $campaign = Campaign::factory()->create();

    $oldProduction = Production::factory()
        ->for($employee)
        ->for($campaign)
        ->for($supervisor)
        ->state(['date' => now()->subMonth()])
        ->create();

    $recentProduction = Production::factory()
        ->for($employee)
        ->for($campaign)
        ->for($supervisor)
        ->state(['date' => now()])
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\Productions\Pages\ListProductions::class)
        ->filterTable('date', [
            'date_from' => now()->subWeek()->format('Y-m-d'),
            'date_until' => now()->format('Y-m-d'),
        ])
        ->assertCanSeeTableRecords([$recentProduction])
        ->assertCanNotSeeTableRecords([$oldProduction]);
});

test('supervisor can filter production by employee', function () {
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

    $campaign = Campaign::factory()->create();

    $production1 = Production::factory()
        ->for($employee1)
        ->for($campaign)
        ->for($supervisor)
        ->create();

    $production2 = Production::factory()
        ->for($employee2)
        ->for($campaign)
        ->for($supervisor)
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\Productions\Pages\ListProductions::class)
        ->filterTable('employee_id', value: $employee1->id)
        ->assertCanSeeTableRecords([$production1])
        ->assertCanNotSeeTableRecords([$production2]);
});

test('supervisor can filter production by campaign', function () {
    $supervisor = Supervisor::factory()->create();
    $supervisorUser = User::factory()->for($supervisor)->create();

    $employee = Employee::factory()
        ->for($supervisor)
        ->state(['status' => EmployeeStatuses::Hired])
        ->create();

    $campaign1 = Campaign::factory()->create();
    $campaign2 = Campaign::factory()->create();

    $production1 = Production::factory()
        ->for($employee)
        ->for($campaign1)
        ->for($supervisor)
        ->create();

    $production2 = Production::factory()
        ->for($employee)
        ->for($campaign2)
        ->for($supervisor)
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\Productions\Pages\ListProductions::class)
        ->filterTable('campaign_id', value: $campaign1->id)
        ->assertCanSeeTableRecords([$production1])
        ->assertCanNotSeeTableRecords([$production2]);
});

test('production table displays correct columns', function () {
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
        ->create();

    Livewire::actingAs($supervisorUser)
        ->test(\App\Filament\Supervisor\Resources\Productions\Pages\ListProductions::class)
        ->assertCanSeeTableColumns([
            'employee.full_name',
            'date',
            'campaign.name',
            'conversions',
            'total_time',
            'talk_time',
            'billable_time',
            'revenue',
            'revenue_rate',
            'sph_goal',
        ]);
});
