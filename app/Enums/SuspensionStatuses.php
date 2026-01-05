<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;

enum SuspensionStatuses: string implements EnumContract, HasColor
{
    use EnumNames, EnumToArray, EnumValues;

    case Pending = 'Pending';
    case Current = 'Current';
    case Completed = 'Completed';

    public function getColor(): ?string
    {
        return match ($this) {
            self::Pending => 'primary',
            self::Current => 'success',
            self::Completed => 'warning',
        };
    }

    // public function getIcon(): ?string
    // {
    //     return match ($this) {
    //         self::Pending => 'heroicon-check-circle',
    //         self::Terminated => 'heroicon-x',
    //         self::Completed => 'heroicon-question-mark-circle',
    //     };
    // }
}
