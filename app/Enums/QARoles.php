<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasLabel;

enum QARoles: string implements EnumContract, HasLabel
{
    use EnumNames, EnumToArray, EnumValues;

    case Manager = 'Quality Assurance Manager';
    case Agent = 'Quality Assurance Agent';

    public function getLabel(): string
    {
        return match ($this) {
            self::Manager => __('enums.qa_role.manager'),
            self::Agent => __('enums.qa_role.agent'),
        };
    }
}
