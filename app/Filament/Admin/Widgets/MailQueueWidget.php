<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MailQueueWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Pending Mails', $this->getPendingMailJobs())
                ->description('Queued email jobs')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('info'),
            Stat::make('Failed Mails', $this->getFailedMailJobs())
                ->description('Failed email jobs')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),
            Stat::make('Queue Status', $this->getQueueStatus())
                ->description('Overall queue health')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),
            Stat::make('Avg Queue Time', $this->getAverageQueueTime())
                ->description('Approximate seconds')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }

    private function getPendingMailJobs(): int
    {
        return Cache::remember('admin.mail_queue.pending', now()->addMinutes(2), function (): int {
            try {
                return DB::table('jobs')
                    ->where('queue', config('queue.default', 'default'))
                    ->count();
            } catch (\Exception) {
                return 0;
            }
        });
    }

    private function getFailedMailJobs(): int
    {
        return Cache::remember('admin.mail_queue.failed', now()->addMinutes(5), function (): int {
            try {
                return DB::table('failed_jobs')
                    ->where('payload', 'like', '%Mailable%')
                    ->orWhere('payload', 'like', '%Mail%')
                    ->count();
            } catch (\Exception) {
                return 0;
            }
        });
    }

    private function getQueueStatus(): string
    {
        try {
            $pendingCount = $this->getPendingMailJobs();
            $failedCount = $this->getFailedMailJobs();

            if ($pendingCount === 0 && $failedCount === 0) {
                return 'Healthy';
            } elseif ($failedCount > $pendingCount) {
                return 'Degraded';
            }

            return 'Active';
        } catch (\Exception) {
            return 'Unknown';
        }
    }

    private function getAverageQueueTime(): string
    {
        return Cache::remember('admin.mail_queue.avg_time', now()->addMinutes(10), function (): string {
            try {
                $jobs = DB::table('jobs')
                    ->select('created_at')
                    ->limit(100)
                    ->get();

                if ($jobs->isEmpty()) {
                    return '—';
                }

                $avgSeconds = $jobs
                    ->map(fn ($job) => now()->diffInSeconds($job->created_at))
                    ->avg();

                return (int) $avgSeconds.'s';
            } catch (\Exception) {
                return 'N/A';
            }
        });
    }
}
