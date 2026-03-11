<?php

namespace App\Filament\Workforce\Widgets;

use App\Enums\DowntimeStatuses;
use App\Enums\RevenueTypes;
use App\Models\Campaign;
use App\Models\Downtime;
use App\Models\Production;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class WorkforceStatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $last30Days = Carbon::today()->subDays(30);

        $pendingDowntimes = Downtime::query()
            ->where('status', DowntimeStatuses::Pending)
            ->count();

        $todayDowntimeMinutes = Downtime::query()
            ->whereDate('date', $today)
            ->sum('total_time');

        $todayApprovedMinutes = Downtime::query()
            ->whereDate('date', $today)
            ->where('status', DowntimeStatuses::Approved)
            ->sum('total_time');

        $last30DaysTotalHours = Production::query()
            ->whereDate('date', '>=', $last30Days)
            ->sum('total_time');

        $last30DaysRevenue = Production::query()
            ->whereDate('date', '>=', $last30Days)
            ->sum('revenue');

        $activeDowntimeCampaigns = Campaign::query()
            ->where('revenue_type', RevenueTypes::Downtime)
            ->count();

        return [
            Stat::make('Pending downtimes', $pendingDowntimes)
                ->description('Awaiting approval')
                ->color($pendingDowntimes > 0 ? 'warning' : 'success'),
            Stat::make('Today\'s downtime (min)', number_format($todayDowntimeMinutes))
                ->description('All requested today')
                ->color('info'),
            Stat::make('Approved today (min)', number_format($todayApprovedMinutes))
                ->description('Approved today')
                ->color('success'),
            Stat::make('Total Hours last 30 days', number_format($last30DaysTotalHours, 2))
                ->description('Across all campaigns')
                ->color($last30DaysTotalHours > 0 ? 'info' : 'secondary'),
            Stat::make('Revenue last 30 days', '$'.number_format($last30DaysRevenue / 100, 2))
                ->description('Total production revenue')
                ->color($last30DaysRevenue > 0 ? 'success' : 'secondary'),
            Stat::make('Downtime campaigns', $activeDowntimeCampaigns)
                ->description('Revenue type: downtime')
                ->color('secondary'),
        ];
    }
}
