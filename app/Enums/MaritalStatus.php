<?php

namespace App\Enums;

use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumValues;
use App\Enums\Traits\EnumToArray;
use App\Enums\Contracts\EnumContract;

enum MaritalStatus: string implements EnumContract
{
    use EnumNames, EnumValues, EnumToArray;

    case Single = 'Single';
    case Married = 'Married';
    case Divorced = 'Divorced';
    case FreeUnion = 'Free Union';
}
