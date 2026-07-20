<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EvaluationStatuses: string implements EnumContract, HasColor, HasLabel
{
    use EnumNames, EnumToArray, EnumValues;

    case Draft = 'draft';
    case Published = 'published';
    case AcceptedClosed = 'accepted_closed';
    case Disputed = 'disputed';
    case Rejected = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => __('enums.evaluation_status.draft'),
            self::Published => __('enums.evaluation_status.published'),
            self::AcceptedClosed => __('enums.evaluation_status.accepted_closed'),
            self::Disputed => __('enums.evaluation_status.disputed'),
            self::Rejected => __('enums.evaluation_status.rejected'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'info',
            self::AcceptedClosed => 'success',
            self::Disputed => 'warning',
            self::Rejected => 'danger',
        };
    }
}
