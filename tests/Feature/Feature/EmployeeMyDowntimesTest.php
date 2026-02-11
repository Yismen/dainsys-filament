<?php

use App\Enums\DowntimeStatuses;
use App\Enums\RevenueTypes;
use App\Filament\Employee\Pages\MyDowntimes;
use App\Models\Campaign;
use App\Models\Citizenship;
use App\Models\Downtime;
use App\Models\DowntimeReason;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

uses()->group('employee-panel');

beforeEach(function (): void {
    Mail::fake();

    $this->citizenship = Citizenship::factory()->create();
    $this->downtime_reason = DowntimeReason::factory()->create();
});

it('displays downtime data for authenticated employee', function (): void {
    $employee = Employee::factory()->create([
        'citizenship_id' => $this->citizenship->id,
    ]);

    // Create a Hire record to make the employee "Hired"
    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subMonths(3),
    ]);

    $employee->refresh();

    $project = Project::factory()->create();
    $campaign = Campaign::factory()->create([
        'project_id' => $project->id,
        'revenue_type' => RevenueTypes::Downtime,
    ]);

    // Create downtimes with different dates to avoid unique constraint
    Downtime::factory()->create([
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'downtime_reason_id' => $this->downtime_reason->id,
        'date' => now()->subDays(2),
        'total_time' => 4,
        'status' => DowntimeStatuses::Pending,
    ]);

    Downtime::factory()->create([
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'downtime_reason_id' => $this->downtime_reason->id,
        'date' => now()->subDays(1),
        'total_time' => 8,
        'status' => DowntimeStatuses::Approved,
    ]);

    Downtime::factory()->create([
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'downtime_reason_id' => $this->downtime_reason->id,
        'date' => now(),
        'total_time' => 2,
        'status' => DowntimeStatuses::Pending,
    ]);

    $user = User::factory()->create([
        'employee_id' => $employee->id,
    ])->load('employee');

    $this->actingAs($user);

    $response = $this->get(MyDowntimes::getUrl(panel: 'employee'));

    $response->assertSuccessful()
        ->assertSee('My Downtimes')
        ->assertSee($project->name);
});

it('prevents access for users without employee_id', function (): void {
    $user = User::factory()->create()->load('employee');

    $this->actingAs($user);

    $response = $this->get(MyDowntimes::getUrl(panel: 'employee'));

    $response->assertForbidden();
});

it('only shows downtime data for authenticated employee', function (): void {
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
        'revenue_type' => RevenueTypes::Downtime,
    ]);

    // Create downtime for employee 1
    Downtime::factory()->create([
        'employee_id' => $employee1->id,
        'campaign_id' => $campaign->id,
        'downtime_reason_id' => $this->downtime_reason->id,
        'date' => now()->subDays(1),
        'total_time' => 4,
    ]);

    // Create downtime for employee 2
    Downtime::factory()->create([
        'employee_id' => $employee2->id,
        'campaign_id' => $campaign->id,
        'downtime_reason_id' => $this->downtime_reason->id,
        'date' => now(),
        'total_time' => 8,
    ]);

    $user = User::factory()->create([
        'employee_id' => $employee1->id,
    ])->load('employee');

    $this->actingAs($user);

    $response = $this->get(MyDowntimes::getUrl(panel: 'employee'));

    $response->assertSuccessful()
        ->assertSee('4.00') // Employee 1's total time
        ->assertDontSee('8.00'); // Employee 2's total time should not be visible
});

it('displays downtime status badge', function (): void {
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
        'revenue_type' => RevenueTypes::Downtime,
    ]);

    // Create downtime with Approved status
    Downtime::factory()->create([
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'downtime_reason_id' => $this->downtime_reason->id,
        'total_time' => 4,
        'status' => DowntimeStatuses::Approved,
    ]);

    $user = User::factory()->create([
        'employee_id' => $employee->id,
    ])->load('employee');

    $this->actingAs($user);

    $response = $this->get(MyDowntimes::getUrl(panel: 'employee'));

    $response->assertSuccessful()
        ->assertSee('Approved');
});
