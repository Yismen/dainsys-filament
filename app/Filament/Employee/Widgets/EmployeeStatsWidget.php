<?php

namespace App\Filament\Employee\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class EmployeeStatsWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            return [];
        }

        $totalHoursThisMonth = Cache::rememberForever(
            "employee.{$employee->id}.total_hours.month",
            fn () => $employee->payrollHours()
                ->whereYear('date', Carbon::now()->year)
                ->whereMonth('date', Carbon::now()->month)
                ->sum('total_hours')
        );

        $productionConversionsThisMonth = Cache::rememberForever(
            "employee.{$employee->id}.production_conversions.month",
            fn () => $employee->productions()
                ->whereYear('date', Carbon::now()->year)
                ->whereMonth('date', Carbon::now()->month)
                ->sum('conversions')
        );

        $pendingDowntimes = Cache::rememberForever(
            "employee.{$employee->id}.pending_downtimes.count",
            fn () => $employee->downtimes()
                ->where('status', 'pending')
                ->count()
        );

        return [
            Stat::make('Total Hours (This Month)', round($totalHoursThisMonth, 2))
                ->description('Hours worked this month')
                ->color('info'),

            Stat::make('Production Sales (This Month)', (int) $productionConversionsThisMonth)
                ->description('Conversions made this month')
                ->color('success'),

            Stat::make('Pending Downtimes', $pendingDowntimes)
                ->description('Downtimes awaiting review')
                ->color($pendingDowntimes > 0 ? 'warning' : 'success'),

            Stat::make('Status', $employee->status->name)
                ->description('Current employment status')
                ->color(match ($employee->status->value) {
                    'hired' => 'success',
                    'suspended' => 'warning',
                    'terminated' => 'danger',
                    default => 'info',
                }),
        ];
    }
}
