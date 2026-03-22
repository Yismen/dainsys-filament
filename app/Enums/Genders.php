<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasIcon;

enum Genders: string implements EnumContract, HasIcon
{
    use EnumNames, EnumToArray, EnumValues;

    case Male = 'Male';
    case Female = 'Female';
    // case Undefined = 'undefined';

    public function getIcon(): string
    {
        return match ($this) {
            self::Male => 'heroicon-o-user-plus',
            self::Female => 'heroicon-o-user-minus',
        // self::Undefined => 'heroicon-o-question-mark-circle',
        };
    }

    public function getColor(): array|string
    {
        return match ($this) {
            self::Male => Color::Blue,
            self::Female => Color::Pink,
        };
    }
}
