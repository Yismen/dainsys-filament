<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;

enum EmployeeStatuses: string implements EnumContract, HasColor
{
    use EnumNames, EnumToArray, EnumValues;

    case Created = 'Created';
    case Hired = 'Hired';
    case Suspended = 'Suspended';
    case Terminated = 'Terminated';

    public function getColor(): ?string
    {
        return match ($this) {
            self::Created => 'primary',
            self::Hired => 'success',
            self::Suspended => 'warning',
            self::Terminated => 'danger',
        };
    }

    // public function getIcon(): ?string
    // {
    //     return match ($this) {
    //         self::Created => 'heroicon-check-circle',
    //         self::Terminated => 'heroicon-x',
    //         self::Suspended => 'heroicon-question-mark-circle',
    //     };
    // }
}
