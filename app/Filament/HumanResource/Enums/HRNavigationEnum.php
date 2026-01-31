<?php

namespace App\Filament\HumanResource\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum HRNavigationEnum: string implements HasIcon, HasLabel
{
    case HUMAN_RESOURCE_DASHBOARD = 'human-resource-dashboard';
    case EMPLOYEES_MANAGEMENT = 'employees-management';
    case HR_MANAGEMENT = 'hr-management';

    public function getLabel(): string
    {
        return match ($this) {
            self::HUMAN_RESOURCE_DASHBOARD => __('Dashboard'),
            self::EMPLOYEES_MANAGEMENT => __('Employees Management'),
            self::HR_MANAGEMENT => __('HR Management'),
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::HUMAN_RESOURCE_DASHBOARD => Heroicon::OutlinedChartBar,
            self::EMPLOYEES_MANAGEMENT => Heroicon::OutlinedUsers,
            self::HR_MANAGEMENT => Heroicon::OutlinedBuildingOffice,
        };
    }
}
