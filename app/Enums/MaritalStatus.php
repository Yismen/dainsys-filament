<?php

namespace App\Enums;

use App\Enums\Traits\AsEnum;
use App\Enums\Contracts\EnumContract;

enum MaritalStatus: string implements EnumContract
{
    use AsEnum;

    case Single = 'Single';
    case Maried = 'Married';
    case Divorced = 'Divorced';
    case Free_Union = 'Free Union';
}
