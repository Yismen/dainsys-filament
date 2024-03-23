<?php

namespace App\Enums;

use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumValues;
use App\Enums\Traits\EnumToArray;
use App\Enums\Contracts\EnumContract;

enum RevenueTypes: string implements EnumContract
{
    use EnumNames, EnumValues, EnumToArray;

    case LoginTime = 'LoginTime';
    case ProductionTime = 'ProductionTime';
    case TalkTime = 'TalkTime';
    case Sales = 'Sales';
}
