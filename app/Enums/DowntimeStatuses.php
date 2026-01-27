<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;

enum DowntimeStatuses: string implements EnumContract, HasColor
{
    use EnumNames, EnumToArray, EnumValues;

    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';

    public function getColor(): ?string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
        };
    }
}
