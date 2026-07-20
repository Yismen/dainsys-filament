<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TerminationTypes: string implements EnumContract, HasColor, HasLabel
{
    use EnumNames, EnumToArray, EnumValues;

    case Resignation = 'resignation';
    case Termination = 'termination';
    case Firing = 'firing';
    case Abandonment = 'abandonment';
    case Dismissing = 'dismissing';

    public function getLabel(): string
    {
        return match ($this) {
            self::Resignation => __('enums.termination.resignation'),
            self::Termination => __('enums.termination.termination'),
            self::Firing => __('enums.termination.firing'),
            self::Abandonment => __('enums.termination.abandonment'),
            self::Dismissing => __('enums.termination.dismissing'),
        };
    }

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
            self::Resignation => __('enums.termination.resignation_description'),
            self::Termination => __('enums.termination.termination_description'),
            self::Firing => __('enums.termination.firing_description'),
            self::Abandonment => __('enums.termination.abandonment_description'),
            self::Dismissing => __('enums.termination.dismissing_description'),
        };
    }
}
