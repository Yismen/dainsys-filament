<?php

namespace App\Filament\Recruitment\Enums;

use Filament\Support\Contracts\HasLabel;

enum RecruitmentNavigationEnum: string implements HasLabel
{
    case RecruitmentDashboard = 'recruitment-dashboard';
    case Recruitment = 'recruitment';
    case Configuration = 'configuration';

    public function getLabel(): string
    {
        return match ($this) {
            self::RecruitmentDashboard => __('Dashboard'),
            self::Recruitment => __('Recruitment'),
            self::Configuration => __('Configuration'),
        };
    }
}
