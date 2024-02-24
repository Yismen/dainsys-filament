<?php

namespace App\Enums;

use App\Enums\Traits\AsEnum;
use App\Enums\Contracts\EnumContract;


enum EmployeeStatus: string implements EnumContract
{
    use AsEnum;

    case Current = 'Current';
    case Inactive = 'Inactive';
    case Suspended = 'Suspended';
}
