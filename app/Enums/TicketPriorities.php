<?php

namespace App\Enums;

use App\Enums\Contracts\EnumContract;
use App\Enums\Traits\EnumNames;
use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\EnumValues;
use Filament\Support\Contracts\HasLabel;

enum TicketPriorities: int implements EnumContract, HasLabel
{
    use EnumNames;
    use EnumToArray;
    use EnumValues;

    case Normal = 1;
    case Medium = 2;
    case High = 3;
    case Emergency = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::Normal => __('enums.ticket_priority.normal'),
            self::Medium => __('enums.ticket_priority.medium'),
            self::High => __('enums.ticket_priority.high'),
            self::Emergency => __('enums.ticket_priority.emergency'),
        };
    }

    public function class(): string
    {
        return match ($this) {
            self::Normal => 'text-black',
            self::Medium => 'badge badge-info',
            self::High => 'badge badge-warning',
            self::Emergency => 'badge badge-danger',
        };
    }

    public function period()
    {
        return match ($this) {
            self::Normal => '48 '.__('support::messages.hours'),
            self::Medium => '24 '.__('support::messages.hours'),
            self::High => '4 '.__('support::messages.hours'),
            self::Emergency => '30 '.__('support::messages.minutes'),
        };
    }
}
