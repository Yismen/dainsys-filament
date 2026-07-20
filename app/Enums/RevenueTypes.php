<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasLabel;

enum RevenueTypes: string implements EnumContract, HasLabel
{
    use EnumNames, EnumToArray, EnumValues;

    case Downtime = 'downtime';
    case LoginTime = 'login time';
    case ProductionTime = 'production time';
    case TalkTime = 'talk time';
    case Conversions = 'conversions';

    public function getLabel(): string
    {
        return match ($this) {
            self::Downtime => __('enums.revenue_type.downtime'),
            self::LoginTime => __('enums.revenue_type.login_time'),
            self::ProductionTime => __('enums.revenue_type.production_time'),
            self::TalkTime => __('enums.revenue_type.talk_time'),
            self::Conversions => __('enums.revenue_type.conversions'),
        };
    }
}
