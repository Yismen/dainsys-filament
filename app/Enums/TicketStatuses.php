<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;

enum TicketStatuses: int implements EnumContract
{
    use EnumNames;
    use EnumToArray;
    use EnumValues;

    case Pending = 1;
    case PendingExpired = 2;
    case InProgress = 3;
    case InProgressExpired = 4;
    case Completed = 5;
    case CompletedExpired = 6;

    public function class(): string
    {
        return match ($this) {
            self::Pending => '',
            self::PendingExpired => 'text-bold text-danger',
            self::InProgress => 'badge badge-info',
            self::InProgressExpired => 'badge badge-warning',
            self::Completed => 'badge badge-success',
            self::CompletedExpired => 'badge badge-danger',
        };
    }
}
