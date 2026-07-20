<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Genders: string implements EnumContract, HasIcon, HasLabel
{
    use EnumNames, EnumToArray, EnumValues;

    case Male = 'Male';
    case Female = 'Female';

    public function getLabel(): string
    {
        return match ($this) {
            self::Male => __('enums.gender.male'),
            self::Female => __('enums.gender.female'),
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Male => 'heroicon-o-user-plus',
            self::Female => 'heroicon-o-user-minus',
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
