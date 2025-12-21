<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;

enum SalaryTypes: string implements EnumContract
{
    use EnumNames, EnumToArray, EnumValues;

    case Salary = 'salary';
    case Hourly = 'hourly';
    case BySales = 'by sales';
}
