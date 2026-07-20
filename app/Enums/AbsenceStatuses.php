<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AbsenceStatuses: string implements EnumContract, HasColor, HasLabel
{
    use EnumNames, EnumToArray, EnumValues;

    case Created = 'Created';
    case Reported = 'Reported';

    public function getLabel(): string
    {
        return match ($this) {
            self::Created => __('enums.absence_status.created'),
            self::Reported => __('enums.absence_status.reported'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Created => 'warning',
            self::Reported => 'success',
        };
    }
}
