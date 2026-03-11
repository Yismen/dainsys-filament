<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;

enum AbsenceTypes: string implements EnumContract, HasColor
{
    use EnumNames, EnumToArray, EnumValues;

    case Justified = 'Justified';
    case Unjustified = 'Unjustified';

    public function getColor(): ?string
    {
        return match ($this) {
            self::Justified => 'success',
            self::Unjustified => 'danger',
        };
    }
}
