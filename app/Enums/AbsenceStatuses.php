<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;

enum AbsenceStatuses: string implements EnumContract, HasColor
{
    use EnumNames, EnumToArray, EnumValues;

    case Created = 'Created';
    case Reported = 'Reported';

    public function getColor(): ?string
    {
        return match ($this) {
            self::Created => 'warning',
            self::Reported => 'success',
        };
    }
}
