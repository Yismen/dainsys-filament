<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;

enum RevenueTypes: string implements EnumContract
{
    use EnumNames, EnumToArray, EnumValues;

    case Downtime = 'downtime';
    case LoginTime = 'login time';
    case ProductionTime = 'production time';
    case TalkTime = 'talk time';
    case Conversions = 'conversions';
}
