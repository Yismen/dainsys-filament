<?php

use App\Filament\Employee\Pages\MyMetrics;
use App\Models\Campaign;
use App\Models\Citizenship;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Production;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses()->group('employee-panel');

beforeEach(function (): void {
    Mail::fake();
});

it('displays aggregated metrics for the authenticated employee', function (): void {
    $citizenship = Citizenship::factory()->create();

    $employee = Employee::factory()->create([
        'citizenship_id' => $citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employee->id,
        'date' => now()->subMonths(3),
    ]);

    $employee->refresh();

    $campaign = Campaign::factory()->create();

    Production::factory()->create([
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'date' => now()->startOfWeek()->addDay(),
        'conversions' => 11,
        'conversions_goal' => 20,
        'total_time' => 5,
        'production_time' => 4,
        'billable_time' => 3,
    ]);

    Production::factory()->create([
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'date' => now()->startOfWeek()->addDays(2),
        'conversions' => 13,
        'conversions_goal' => 20,
        'total_time' => 5,
        'production_time' => 4,
        'billable_time' => 3,
    ]);

    /** @var User $user */
    $user = User::factory()->create([
        'employee_id' => $employee->id,
    ]);
    $user->load('employee');

    actingAs($user);

    $response = get(MyMetrics::getUrl(panel: 'employee'));

    $response->assertSuccessful()
        ->assertSee('My Metrics')
        ->assertSee('24.00');
});

it('prevents access for users without employee id', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'employee_id' => null,
    ]);

    actingAs($user);

    get(MyMetrics::getUrl(panel: 'employee'))
        ->assertForbidden();
});

it('shows only authenticated employee metrics', function (): void {
    $citizenship = Citizenship::factory()->create();

    $employeeOne = Employee::factory()->create([
        'citizenship_id' => $citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employeeOne->id,
        'date' => now()->subMonths(3),
    ]);

    $employeeTwo = Employee::factory()->create([
        'citizenship_id' => $citizenship->id,
    ]);

    Hire::factory()->create([
        'employee_id' => $employeeTwo->id,
        'date' => now()->subMonths(3),
    ]);

    $campaign = Campaign::factory()->create();

    Production::factory()->create([
        'employee_id' => $employeeOne->id,
        'campaign_id' => $campaign->id,
        'date' => now()->startOfWeek()->addDay(),
        'conversions' => 24,
        'conversions_goal' => 20,
        'total_time' => 5,
        'production_time' => 4,
        'billable_time' => 3,
    ]);

    Production::factory()->create([
        'employee_id' => $employeeTwo->id,
        'campaign_id' => $campaign->id,
        'date' => now()->startOfWeek()->addDay(),
        'conversions' => 99,
        'conversions_goal' => 20,
        'total_time' => 5,
        'production_time' => 4,
        'billable_time' => 3,
    ]);

    /** @var User $user */
    $user = User::factory()->create([
        'employee_id' => $employeeOne->id,
    ]);
    $user->load('employee');

    actingAs($user);

    $response = get(MyMetrics::getUrl(panel: 'employee'));

    $response->assertSuccessful()
        ->assertSee('24.00')
        ->assertDontSee('99.00');
});
