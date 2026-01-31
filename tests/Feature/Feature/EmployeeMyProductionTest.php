<?php

use App\Filament\Employee\Pages\MyProduction;
use App\Models\Campaign;
use App\Models\Citizenship;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Production;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

uses()->group('employee-panel');

beforeEach(function () {
    Mail::fake();

    $this->citizenship = Citizenship::factory()->create();
});

it('displays production data for authenticated employee', function () {
    $employee = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    // Create a Hire record to make the employee "Hired"
    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subMonths(3),
    ]);

    $employee->refresh(); // Reload to get updated status from boot

    $project = Project::factory()->create();
    $campaign = Campaign::factory()->create([
        'project_id' => $project->id,
        'sph_goal' => 10.0,
    ]);

    // Create productions with different dates to avoid unique constraint
    Production::factory()->create([
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'date' => now()->subDays(2),
        'conversions' => 50,
        'billable_time' => 5,
    ]);

    Production::factory()->create([
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'date' => now()->subDays(1),
        'conversions' => 30,
        'billable_time' => 3,
    ]);

    Production::factory()->create([
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'date' => now(),
        'conversions' => 40,
        'billable_time' => 4,
    ]);

    $user = User::factory()->create([
        'employee_id' => $employee->id,
    ])->load('employee');

    $this->actingAs($user);

    $response = $this->get(MyProduction::getUrl(panel: 'employee'));

    $response->assertSuccessful()
        ->assertSee('My Production')
        ->assertSee($project->name);
});

it('prevents access for users without employee_id', function () {
    $user = User::factory()->create([
        'employee_id' => null,
    ]);

    $this->actingAs($user);

    $this->get(MyProduction::getUrl(panel: 'employee'))
        ->assertForbidden();
});

it('only shows production data for authenticated employee', function () {
    $employee1 = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    // Create a Hire record to make employee1 "Hired"
    Hire::factory()->create([
        'employee_id' => $employee1->id,
        'date' => now()->subMonths(3),
    ]);

    $employee1->refresh();

    $employee2 = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    // Create a Hire record to make employee2 "Hired"
    Hire::factory()->create([
        'employee_id' => $employee2->id,
        'date' => now()->subMonths(3),
    ]);

    $employee2->refresh();

    $campaign = Campaign::factory()->create([
        'sph_goal' => 10.0,
    ]);

    // Create production for employee 1
    Production::factory()->create([
        'employee_id' => $employee1->id,
        'campaign_id' => $campaign->id,
        'date' => now()->subDays(1),
        'conversions' => 50,
    ]);

    // Create production for employee 2
    $employee2Production = Production::factory()->create([
        'employee_id' => $employee2->id,
        'campaign_id' => $campaign->id,
        'date' => now(),
        'conversions' => 100,
    ]);

    $user = User::factory()->create([
        'employee_id' => $employee1->id,
    ])->load('employee');

    $this->actingAs($user);

    $response = $this->get(MyProduction::getUrl(panel: 'employee'));

    $response->assertSuccessful()
        ->assertSee('50.00') // Employee 1's conversions
        ->assertDontSee('100.00'); // Employee 2's conversions should not be visible
});

it('displays calculated percentage to goal', function () {
    $employee = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    // Create a Hire record to make the employee "Hired"
    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subMonths(3),
    ]);

    $employee->refresh();

    $campaign = Campaign::factory()->create([
        'sph_goal' => 10.0,
    ]);

    // Create production with 50 conversions in 5 hours = 10 SPH (100% to goal)
    Production::factory()->create([
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'conversions' => 50,
        'billable_time' => 5,
    ]);

    $user = User::factory()->create([
        'employee_id' => $employee->id,
    ])->load('employee');

    $this->actingAs($user);

    $response = $this->get(MyProduction::getUrl(panel: 'employee'));

    $response->assertSuccessful()
        ->assertSee('% to Goal')
        ->assertSee('100.0%');
});
