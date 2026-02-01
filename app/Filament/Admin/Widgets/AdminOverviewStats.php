<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class AdminOverviewStats extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', $this->getTotalUsers())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
            Stat::make('Total Roles', $this->getTotalRoles())
                ->description('Available roles')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('success'),
            Stat::make('Active Sessions', $this->getActiveSessions())
                ->description('From Sanctum tokens')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('info'),
            Stat::make('Database Records', $this->getDatabaseSize())
                ->description('Approximate count')
                ->descriptionIcon(Heroicon::OutlinedQueueList)
                ->color('warning'),
        ];
    }

    private function getTotalUsers(): int
    {
        return Cache::remember('admin.total_users', now()->addHours(3), function (): int {
            return User::count();
        });
    }

    private function getTotalRoles(): int
    {
        return Cache::remember('admin.total_roles', now()->addHours(3), function (): int {
            return Role::count();
        });
    }

    private function getActiveSessions(): int
    {
        return Cache::remember('admin.active_sessions', now()->addMinutes(10), function (): int {
            return \Laravel\Sanctum\PersonalAccessToken::where('last_used_at', '>=', now()->subHours(1))->count();
        });
    }

    private function getDatabaseSize(): string
    {
        return Cache::remember('admin.database_size', now()->addHours(12), function (): string {
            try {
                $totalRecords = User::count() + Role::count();

                return $totalRecords > 0 ? number_format($totalRecords) : '0';
            } catch (\Exception) {
                return 'N/A';
            }
        });
    }
}
