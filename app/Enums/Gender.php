<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;

enum Gender: string implements EnumContract
{
    use EnumNames, EnumToArray, EnumValues;

    case Male = 'Male';
    case Female = 'Female';
    // case Undefined = 'Undefined';
}
