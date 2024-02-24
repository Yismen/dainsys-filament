<?php

namespace App\Enums;

use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumValues;
use App\Enums\Traits\EnumToArray;
use App\Enums\Contracts\EnumContract;

enum EmployeeStatus: string implements EnumContract
{
    use EnumNames, EnumValues, EnumToArray;

    case Current = 'Current';
    case Inactive = 'Inactive';
    case Suspended = 'Suspended';
}
