<?php

namespace App\Enums;

use App\Models\Production;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumValues;
use App\Enums\Traits\EnumToArray;
use App\Enums\Contracts\EnumContract;

enum RevenueTypes: string implements EnumContract
{
    use EnumNames, EnumValues, EnumToArray;

    case Downtime = 'downtime';
    case LoginTime = 'login time';
    case ProductionTime = 'production time';
    case TalkTime = 'talk time';
    case Conversions = 'conversions';

    public function calculateRevenue(Production $production): float
    {
        return match ($this) {
            self::Downtime => 0,
            self::LoginTime => $production->total_time * $production->campaign->revenue_rate,
            self::ProductionTime => $production->production_time * $production->campaign->revenue_rate,
            self::TalkTime => $production->talk_time * $production->campaign->revenue_rate,
            self::Conversions => $production->conversions * $production->campaign->revenue_rate,
        };
    }

    public function productionRevenue(Production $production): float
    {
        return match ($this) {
            self::Downtime => $production->total_time * $production->campaign->revenue_rate,
            self::LoginTime => $production->total_time * $production->campaign->revenue_rate,
            self::ProductionTime => $production->production_time * $production->campaign->revenue_rate,
            self::TalkTime => $production->talk_time * $production->campaign->revenue_rate,
            self::Conversions => $production->conversions * $production->campaign->revenue_rate,
        };
    }

    public function calculateBillableHours(Production $production): float
    {
        return match ($this) {
            self::Downtime => 0,
            self::LoginTime => $production->total_time ?? 0,
            self::ProductionTime => $production->production_time ?? 0,
            self::TalkTime => $production->talk_time ?? 0,
            self::Conversions => $production->production_time ?? 0,
        };
    }
}
