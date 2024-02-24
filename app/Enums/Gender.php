<?php

namespace App\Enums;

use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumValues;
use App\Enums\Traits\EnumToArray;
use App\Enums\Contracts\EnumContract;

enum Gender: string implements EnumContract
{
    use EnumNames, EnumValues, EnumToArray;

    case Male = 'Male';
    case Female = 'Female';
    // case Undefined = 'Undefined';
}
