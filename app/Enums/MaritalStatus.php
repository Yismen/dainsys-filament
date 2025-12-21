<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;

enum MaritalStatus: string implements EnumContract
{
    use EnumNames, EnumToArray, EnumValues;

    case Single = 'Single';
    case Married = 'Married';
    case Divorced = 'Divorced';
    case FreeUnion = 'Free Union';
}
