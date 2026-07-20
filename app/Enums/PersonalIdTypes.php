<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasLabel;

enum PersonalIdTypes: string implements EnumContract, HasLabel
{
    use EnumNames, EnumToArray, EnumValues;

    case DominicanId = 'dominican id';
    case Passport = 'passport';

    public function getLabel(): string
    {
        return match ($this) {
            self::DominicanId => __('enums.personal_id_type.dominican_id'),
            self::Passport => __('enums.personal_id_type.passport'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DominicanId => 'primary',
            self::Passport => 'info',
        };
    }
}
