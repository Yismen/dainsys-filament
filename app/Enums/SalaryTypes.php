<?php

namespace App\Enums;

use App\Models\Performance;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumValues;
use App\Enums\Traits\EnumToArray;
use App\Enums\Contracts\EnumContract;

enum SalaryTypes: string implements EnumContract
{
    use EnumNames, EnumValues, EnumToArray;

    case Salary = 'salary';
    case Hourly = 'hourly';
    case BySales = 'by sales';
}
