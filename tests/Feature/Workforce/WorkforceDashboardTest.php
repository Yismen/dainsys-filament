<?php

use App\Enums\DowntimeStatuses;
use App\Enums\RevenueTypes;
use App\Filament\Workforce\Widgets\DowntimeByReasonChart;
use App\Filament\Workforce\Widgets\PendingDowntimesTable;
use App\Filament\Workforce\Widgets\ProductionRevenueTrendChart;
use App\Filament\Workforce\Widgets\RecentDowntimesTable;
use App\Filament\Workforce\Widgets\WorkforceStatsOverview;
use App\Models\Campaign;
use App\Models\Comment;
use App\Models\Downtime;
use App\Models\DowntimeReason;
use App\Models\Employee;
use App\Models\Production;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Bus::fake();

    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('displays workforce stats overview correctly', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::Downtime]);
    $employee = Employee::factory()->create();

    Downtime::factory()->create([
        'status' => DowntimeStatuses::Pending,
        'date' => Carbon::today(),
        'total_time' => 60,
        'campaign_id' => $campaign->id,
        'employee_id' => $employee->id,
    ]);

    $approvedCampaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::Downtime]);

    Downtime::factory()->create([
        'status' => DowntimeStatuses::Approved,
        'date' => Carbon::today(),
        'total_time' => 120,
        'campaign_id' => $approvedCampaign->id,
        'employee_id' => $employee->id,
    ]);

    Production::factory()->create([
        'date' => Carbon::today(),
        'revenue' => 5000,
        'campaign_id' => $approvedCampaign->id,
        'employee_id' => $employee->id,
    ]);

    Livewire::test(WorkforceStatsOverview::class)
        ->assertSee('Pending downtimes')
        ->assertSee('1')
        ->assertSee('Today\'s downtime (min)')
        ->assertSee('Production revenue today');
});

it('displays pending downtimes table with records', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::Downtime]);
    $employee = Employee::factory()->create();
    $reason = DowntimeReason::factory()->create();

    $downtime = Downtime::factory()->create([
        'status' => DowntimeStatuses::Pending,
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'downtime_reason_id' => $reason->id,
        'total_time' => 45,
    ]);

    Livewire::test(PendingDowntimesTable::class)
        ->assertCanSeeTableRecords([$downtime])
        ->assertSee($employee->full_name)
        ->assertSee($campaign->name)
        ->assertSee($reason->name);
});

it('approves a downtime from pending table', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::Downtime]);
    $employee = Employee::factory()->create();

    $downtime = Downtime::factory()->create([
        'status' => DowntimeStatuses::Pending,
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'total_time' => 60,
    ]);

    Livewire::test(PendingDowntimesTable::class)
        ->callTableAction('approve', $downtime, ['comment' => 'Approved by test'])
        ->assertNotified();

    $downtime->refresh();

    expect($downtime->status)->toBe(DowntimeStatuses::Approved)
        ->and($downtime->aprover_id)->toBe($this->user->id)
        ->and(Comment::where('commentable_id', $downtime->id)->where('text', 'like', '%Approved%')->exists())->toBeTrue();
});

it('rejects a downtime from pending table', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::Downtime]);
    $employee = Employee::factory()->create();

    $downtime = Downtime::factory()->create([
        'status' => DowntimeStatuses::Pending,
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
        'total_time' => 60,
    ]);

    Livewire::test(PendingDowntimesTable::class)
        ->callTableAction('reject', $downtime, ['comment' => 'Rejected by test'])
        ->assertNotified();

    $downtime->refresh();

    expect($downtime->status)->toBe(DowntimeStatuses::Rejected)
        ->and($downtime->aprover_id)->toBeNull()
        ->and(Comment::where('commentable_id', $downtime->id)->where('text', 'like', '%Rejected%')->exists())->toBeTrue();
});

it('displays recent downtimes table', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::Downtime]);
    $employee = Employee::factory()->create();

    $recentDowntime = Downtime::factory()->create([
        'date' => Carbon::today()->subDays(5),
        'status' => DowntimeStatuses::Approved,
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
    ]);

    $oldDowntime = Downtime::factory()->create([
        'date' => Carbon::today()->subDays(20),
        'status' => DowntimeStatuses::Pending,
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
    ]);

    Livewire::test(RecentDowntimesTable::class)
        ->assertCanSeeTableRecords([$recentDowntime])
        ->assertCanNotSeeTableRecords([$oldDowntime]);
});

it('renders downtime by reason chart', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::Downtime]);
    $employee = Employee::factory()->create();
    $reason1 = DowntimeReason::factory()->create(['name' => 'Break']);
    $reason2 = DowntimeReason::factory()->create(['name' => 'Meeting']);

    Downtime::factory()->create([
        'date' => Carbon::today()->subDays(5),
        'downtime_reason_id' => $reason1->id,
        'total_time' => 120,
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
    ]);

    Downtime::factory()->create([
        'date' => Carbon::today()->subDays(10),
        'downtime_reason_id' => $reason2->id,
        'total_time' => 60,
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
    ]);

    Livewire::test(DowntimeByReasonChart::class)
        ->assertSee('Downtime by reason');
});

it('renders production revenue trend chart', function () {
    $campaign = Campaign::factory()->create(['revenue_type' => RevenueTypes::Conversions]);
    $employee = Employee::factory()->create();

    Production::factory()->create([
        'date' => Carbon::today()->subDays(3),
        'revenue' => 10000,
        'billable_time' => 240,
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
    ]);

    Production::factory()->create([
        'date' => Carbon::today()->subDays(7),
        'revenue' => 8000,
        'billable_time' => 180,
        'employee_id' => $employee->id,
        'campaign_id' => $campaign->id,
    ]);

    Livewire::test(ProductionRevenueTrendChart::class)
        ->assertSee('Production revenue trend');
});
