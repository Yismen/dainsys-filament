<?php

namespace App\Enums;

use App\Enums\Traits\AsEnum;
use App\Enums\Contracts\EnumContract;

enum Gender: string implements EnumContract
{
    use AsEnum;

    case Male = 'Male';
    case Female = 'Female';
    // case Undefined = 'Undefined';
}
