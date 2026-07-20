<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AbsenceTypes: string implements EnumContract, HasColor, HasLabel
{
    use EnumNames, EnumToArray, EnumValues;

    case Justified = 'Justified';
    case Unjustified = 'Unjustified';

    public function getLabel(): string
    {
        return match ($this) {
            self::Justified => __('enums.absence_type.justified'),
            self::Unjustified => __('enums.absence_type.unjustified'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Justified => 'success',
            self::Unjustified => 'danger',
        };
    }
}
