<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasLabel;

enum HRActivityTypes: string implements EnumContract, HasLabel
{
    use EnumNames, EnumToArray, EnumValues;

    case Vacations = 'Vacations';
    case Permission = 'Permission';
    case EmploymentLetter = 'Employment Letter';
    case Loan = 'Loan';
    case Uniform = 'Uniform';
    case Counseling = 'Counseling';
    case Interview = 'Interview';

    public function getLabel(): string
    {
        return match ($this) {
            self::Vacations => __('enums.hr_activity_type.vacations'),
            self::Permission => __('enums.hr_activity_type.permission'),
            self::EmploymentLetter => __('enums.hr_activity_type.employment_letter'),
            self::Loan => __('enums.hr_activity_type.loan'),
            self::Uniform => __('enums.hr_activity_type.uniform'),
            self::Counseling => __('enums.hr_activity_type.counseling'),
            self::Interview => __('enums.hr_activity_type.interview'),
        };
    }
}
