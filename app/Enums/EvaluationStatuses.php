<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasColor;

enum EvaluationStatuses: string implements EnumContract, HasColor
{
    use EnumNames, EnumToArray, EnumValues;

    case Draft = 'draft';
    case Published = 'published';
    case AcceptedClosed = 'accepted_closed';
    case Disputed = 'disputed';
    case Rejected = 'rejected';

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
