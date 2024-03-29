<?php

namespace App\Enums;

use App\Models\Performance;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumValues;
use App\Enums\Traits\EnumToArray;
use App\Enums\Contracts\EnumContract;

enum RevenueTypes: string implements EnumContract
{
    use EnumNames, EnumValues, EnumToArray;

    case LoginTime = 'Login Time';
    case ProductionTime = 'Production Time';
    case TalkTime = 'Talk Time';
    case Sales = 'Sales';

    public function performanceRevenue(Performance $performance): float
    {
        return match ($this) {
            self::LoginTime => $performance->login_time * $performance->campaign->rate,
            self::ProductionTime => $performance->production_time * $performance->campaign->rate,
            self::TalkTime => $performance->talk_time * $performance->campaign->rate,
            self::Sales => $performance->successes * $performance->campaign->rate,
        };
    }

    public function performanceBillableTime(Performance $performance): float
    {
        return match ($this) {
            self::LoginTime => $performance->login_time ?? 0,
            self::ProductionTime => $performance->production_time ?? 0,
            self::TalkTime => $performance->talk_time ?? 0,
            self::Sales => $performance->production_time ?? 0,
        };
    }
}
