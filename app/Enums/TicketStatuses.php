<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;

enum TicketStatuses: string implements EnumContract
{
    use EnumNames;
    use EnumToArray;
    use EnumValues;

    case Pending = 'not assigned';
    case PendingExpired = 'expired before assignment';
    case InProgress = 'assigned to user';
    case InProgressExpired = 'expired and assigned';
    case Completed = 'completed in time';
    case CompletedExpired = 'completed after expiring';

    // public function class(): string
    // {
    //     return match ($this) {
    //         self::Pending => '',
    //         self::PendingExpired => 'text-bold text-danger',
    //         self::InProgress => 'badge badge-info',
    //         self::InProgressExpired => 'badge badge-warning',
    //         self::Completed => 'badge badge-success',
    //         self::CompletedExpired => 'badge badge-success',
    //     };
    // }

    public function color(): string
    {
        return match ($this) {
            self::Pending => '',
            self::PendingExpired => 'warning',
            self::InProgress => 'info',
            self::InProgressExpired => 'warning',
            self::Completed => 'success',
            self::CompletedExpired => 'danger',
        };
    }
}
