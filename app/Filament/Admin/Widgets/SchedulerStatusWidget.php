<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SchedulerStatusWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Queue Jobs', $this->getQueueJobsCount())
                ->description('Scheduled queue jobs')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('info'),
            Stat::make('Processed Today', $this->getProcessedToday())
                ->description('Completed today')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Scheduler Health', $this->getSchedulerHealth())
                ->description('Overall status')
                ->descriptionIcon('heroicon-m-heart')
                ->color('success'),
            Stat::make('Last Run', $this->getLastScheduledRun())
                ->description('Hours ago')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary'),
        ];
    }

    private function getQueueJobsCount(): int
    {
        return Cache::remember('admin.scheduler.queue_count', now()->addMinutes(2), function (): int {
            try {
                return DB::table('jobs')->count();
            } catch (\Exception) {
                return 0;
            }
        });
    }

    private function getProcessedToday(): int
    {
        return Cache::remember('admin.scheduler.processed_today', now()->addMinutes(5), function (): int {
            try {
                // Count jobs created and processed today
                return DB::table('jobs')
                    ->where('created_at', '>=', now()->startOfDay())
                    ->count();
            } catch (\Exception) {
                return 0;
            }
        });
    }

    private function getSchedulerHealth(): string
    {
        try {
            $jobCount = $this->getQueueJobsCount();
            $failedCount = DB::table('failed_jobs')->count();

            if ($failedCount === 0 && $jobCount > 0) {
                return 'Excellent';
            } elseif ($failedCount < 5) {
                return 'Good';
            } elseif ($failedCount < 20) {
                return 'Fair';
            }

            return 'Poor';
        } catch (\Exception) {
            return 'Unknown';
        }
    }

    private function getLastScheduledRun(): string
    {
        return Cache::remember('admin.scheduler.last_run', now()->addMinutes(10), function (): string {
            try {
                $lastJob = DB::table('jobs')
                    ->orderByDesc('created_at')
                    ->first();

                if (! $lastJob) {
                    return '—';
                }

                $hours = now()->diffInHours($lastJob->created_at);

                return $hours.'h ago';
            } catch (\Exception) {
                return 'N/A';
            }
        });
    }
}
