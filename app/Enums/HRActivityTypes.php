<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;

enum HRActivityTypes: string implements EnumContract
{
    use EnumNames, EnumToArray, EnumValues;

    case Vacations = 'Vacations';
    case Permission = 'Permission';
    case EmploymentLetter = 'Employment Letter';
    case Loan = 'Loan';
    case Uniform = 'Uniform';
    case Counseling = 'Counseling';
    case Interview = 'Interview';
}
