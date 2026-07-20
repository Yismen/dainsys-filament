<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum DowntimeStatuses: string implements EnumContract, HasColor, HasLabel
{
    use EnumNames, EnumToArray, EnumValues;

    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => __('enums.downtime_status.pending'),
            self::Approved => __('enums.downtime_status.approved'),
            self::Rejected => __('enums.downtime_status.rejected'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
        };
    }
}
