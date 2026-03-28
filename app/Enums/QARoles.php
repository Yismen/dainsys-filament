<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;

enum QARoles: string implements EnumContract
{
    use EnumNames, EnumToArray, EnumValues;

    case Manager = 'Quality Assurance Manager';
    case Agent = 'Quality Assurance Agent';
}
