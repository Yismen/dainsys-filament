<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FailedJobsStatsWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Failed Jobs', $this->getTotalFailedJobs())
                ->description('Total failed jobs')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),
            Stat::make('Today\'s Failures', $this->getTodayFailures())
                ->description('Failed in last 24 hours')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Oldest Failure', $this->getOldestFailure())
                ->description('Days since oldest')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
            Stat::make('Success Rate', $this->getSuccessRate())
                ->description('Approximate success %')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }

    private function getTotalFailedJobs(): int
    {
        return Cache::remember('admin.failed_jobs.total', now()->addMinutes(5), function (): int {
            try {
                return DB::table('failed_jobs')->count();
            } catch (\Exception) {
                return 0;
            }
        });
    }

    private function getTodayFailures(): int
    {
        return Cache::remember('admin.failed_jobs.today', now()->addMinutes(5), function (): int {
            try {
                return DB::table('failed_jobs')
                    ->where('failed_at', '>=', now()->startOfDay())
                    ->count();
            } catch (\Exception) {
                return 0;
            }
        });
    }

    private function getOldestFailure(): string
    {
        return Cache::remember('admin.failed_jobs.oldest', now()->addHours(1), function (): string {
            try {
                $oldest = DB::table('failed_jobs')
                    ->orderBy('failed_at')
                    ->first();

                if (! $oldest) {
                    return '—';
                }

                $difInDays = Carbon::parse($oldest->failed_at)->diffInDays(now());

                return \number_format(abs($difInDays), 0).' days';
            } catch (\Exception) {
                return 'N/A';
            }
        });
    }

    private function getSuccessRate(): string
    {
        return Cache::remember('admin.failed_jobs.success_rate', now()->addHours(1), function (): string {
            try {
                $failedCount = DB::table('failed_jobs')->count();

                if ($failedCount === 0) {
                    return '100%';
                }

                // Estimate success rate (this is a simplification)
                return number_format(max(0, 100 - min(100, $failedCount * 0.5)), 1).'%';
            } catch (\Exception) {
                return 'N/A';
            }
        });
    }
}
