<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasLabel;

enum SalaryTypes: string implements EnumContract, HasLabel
{
    use EnumNames, EnumToArray, EnumValues;

    case Salary = 'salary';
    case Hourly = 'hourly';
    case BySales = 'by sales';

    public function getLabel(): string
    {
        return match ($this) {
            self::Salary => __('enums.salary_type.salary'),
            self::Hourly => __('enums.salary_type.hourly'),
            self::BySales => __('enums.salary_type.by_sales'),
        };
    }
}
