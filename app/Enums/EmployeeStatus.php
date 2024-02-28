<?php

namespace App\Enums;

use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumValues;
use App\Enums\Traits\EnumToArray;
use Filament\Support\Colors\Color;
use App\Enums\Contracts\EnumContract;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum EmployeeStatus: string implements EnumContract, HasColor
{
    use EnumNames, EnumValues, EnumToArray;

    case Current = 'Current';
    case Inactive = 'Inactive';
    case Suspended = 'Suspended';

    public function getColor(): ?string
    {
        return match ($this) {
            self::Current => 'success',
            self::Inactive => 'danger',
            self::Suspended => 'warning',
        };
    }


    // public function getIcon(): ?string
    // {
    //     return match ($this) {
    //         self::Current => 'heroicon-check-circle',
    //         self::Inactive => 'heroicon-x',
    //         self::Suspended => 'heroicon-question-mark-circle',
    //     };
    // }
}
