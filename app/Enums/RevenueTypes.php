<?php

namespace App\Enums;

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
}
