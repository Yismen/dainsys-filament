<?php

namespace App\Enums;

use App\Models\Performance;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumValues;
use App\Enums\Traits\EnumToArray;
use App\Enums\Contracts\EnumContract;

enum PersonalIdTypes: string implements EnumContract
{
    use EnumNames, EnumValues, EnumToArray;

    case DominicanId = 'dominican id';
    case Passport = 'passport';
}
