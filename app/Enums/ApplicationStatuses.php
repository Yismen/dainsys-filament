<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;

enum ApplicationStatuses: string implements EnumContract, HasColor
{
    use EnumNames, EnumToArray, EnumValues;

    case Applied = 'Applied';
    case InReview = 'InReview';
    case InProgress = 'InProgress';
    case Hired = 'Hired';
    case Rejected = 'Rejected';
    case Withdrawn = 'Withdrawn';

    public function getColor(): ?string
    {
        return match ($this) {
            self::Applied => 'info',
            self::InReview => 'warning',
            self::InProgress => 'primary',
            self::Hired => 'success',
            self::Rejected => 'danger',
            self::Withdrawn => 'gray',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Applied => __('filament.applied'),
            self::InReview => __('filament.in_review'),
            self::InProgress => __('filament.in_progress'),
            self::Hired => __('filament.hired'),
            self::Rejected => __('filament.rejected'),
            self::Withdrawn => __('filament.withdrawn'),
        };
    }
}
