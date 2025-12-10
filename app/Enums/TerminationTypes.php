<?php

namespace App\Enums;

use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumValues;
use App\Enums\Traits\EnumToArray;
use Filament\Support\Colors\Color;
use App\Enums\Contracts\EnumContract;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;

enum TerminationTypes: string implements EnumContract, HasColor
{
    use EnumNames, EnumValues, EnumToArray;

    case Resignation = 'resignation'; // Renuncia
    case Termination = 'termination'; // desahucio
    case Firing = 'firing'; // despido
    case Abandonment = 'abandonment'; // abandono
    case Dismissing = 'dismissing'; // dimision

    public function getColor(): ?string
    {
        return match ($this) {
            self::Resignation => 'warning',
            self::Termination => 'warning',
            self::Firing => 'danger',
            self::Abandonment => 'danger',
            self::Dismissing => 'danger',
        };
    }

    public function description(): ?string
    {
        return match ($this) {
            self::Resignation => 'The employee communicated his desire to terminate the contract',
            self::Termination => 'The company has excercised the termination of the contract without cause',
            self::Firing => 'The employee commited a heavy fault, causing his termination. The company has cause to terminate this contract',
            self::Abandonment => 'Multiple injustified absences',
            self::Dismissing => 'The employee sued the company',
        };
    }
}
