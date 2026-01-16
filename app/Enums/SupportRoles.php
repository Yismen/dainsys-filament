<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;

enum SupportRoles: string implements EnumContract
{
    use EnumNames;
    use EnumToArray;
    use EnumValues;

    case Manager = 'Support Manager';
    case Agent = 'Support Agent';
}
