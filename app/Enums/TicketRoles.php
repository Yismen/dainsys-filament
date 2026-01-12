<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;

enum TicketRoles: string implements EnumContract
{
    use EnumNames;
    use EnumToArray;
    use EnumValues;

    case Admin = 'ticket admin';
    case Operator = 'ticket operator';
}
