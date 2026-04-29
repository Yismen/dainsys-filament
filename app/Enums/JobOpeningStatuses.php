<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;

enum JobOpeningStatuses: string implements EnumContract, HasColor
{
    use EnumNames, EnumToArray, EnumValues;

    case Open = 'Open';
    case OnHold = 'OnHold';
    case Closed = 'Closed';
    case Cancelled = 'Cancelled';

    public function getColor(): ?string
    {
        return match ($this) {
            self::Open => 'success',
            self::OnHold => 'warning',
            self::Closed => 'gray',
            self::Cancelled => 'danger',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Open => __('filament.open'),
            self::OnHold => __('filament.on_hold'),
            self::Closed => __('filament.closed'),
            self::Cancelled => __('filament.cancelled'),
        };
    }
}
