<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;

enum StageOutcome: string implements EnumContract, HasColor
{
    use EnumNames, EnumToArray, EnumValues;

    case Passed = 'Passed';
    case Failed = 'Failed';
    case Pending = 'Pending';

    public function getColor(): ?string
    {
        return match ($this) {
            self::Passed => 'success',
            self::Failed => 'danger',
            self::Pending => 'warning',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Passed => __('filament.passed'),
            self::Failed => __('filament.failed'),
            self::Pending => __('filament.pending'),
        };
    }
}
