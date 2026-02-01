<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsOverviewWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Roles', $this->getTotalRoles())
                ->description('Available roles')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('primary'),
            Stat::make('Total Permissions', $this->getTotalPermissions())
                ->description('Permission rules')
                ->descriptionIcon('heroicon-m-lock-closed')
                ->color('success'),
            Stat::make('Avg Perms per Role', $this->getAveragePermissionsPerRole())
                ->description('Permissions distribution')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
            Stat::make('Protected Models', $this->getProtectedModels())
                ->description('Shield protected entities')
                ->descriptionIcon('heroicon-m-shield-exclamation')
                ->color('warning'),
        ];
    }

    private function getTotalRoles(): int
    {
        return Cache::remember('admin.total_roles', now()->addHours(3), function (): int {
            return Role::count();
        });
    }

    private function getTotalPermissions(): int
    {
        return Cache::remember('admin.total_permissions', now()->addHours(3), function (): int {
            return Permission::count();
        });
    }

    private function getAveragePermissionsPerRole(): string
    {
        return Cache::remember('admin.avg_permissions_per_role', now()->addHours(3), function (): string {
            try {
                $totalRoles = Role::count();

                if ($totalRoles === 0) {
                    return '0';
                }

                $totalPermissions = Permission::count();
                $average = $totalPermissions / $totalRoles;

                return number_format($average, 1);
            } catch (\Exception) {
                return 'N/A';
            }
        });
    }

    private function getProtectedModels(): string
    {
        return Cache::remember('admin.protected_models', now()->addHours(6), function (): string {
            try {
                // Count distinct models from the permission names
                $permissions = Permission::pluck('name')->toArray();
                $models = collect($permissions)
                    ->map(function ($permission) {
                        // Extract model name from permission like "view_user" -> "user"
                        preg_match('/_(\w+)$/', $permission, $matches);

                        return $matches[1] ?? null;
                    })
                    ->filter()
                    ->unique()
                    ->count();

                return (string) $models;
            } catch (\Exception) {
                return 'N/A';
            }
        });
    }
}
