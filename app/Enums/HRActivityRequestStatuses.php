<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum HRActivityRequestStatuses: string implements HasColor, HasIcon, HasLabel
{
    case Requested = 'Requested';
    case InProgress = 'In Progress';
    case Completed = 'Completed';
    case Cancelled = 'Cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Requested => __('enums.hr_activity_request_status.requested'),
            self::InProgress => __('enums.hr_activity_request_status.in_progress'),
            self::Completed => __('enums.hr_activity_request_status.completed'),
            self::Cancelled => __('enums.hr_activity_request_status.cancelled'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Requested => Color::Blue,
            self::InProgress => Color::Yellow,
            self::Completed => Color::Green,
            self::Cancelled => Color::Gray,
        };
    }

    public function getIcon(): string|BackedEnum|null
    {
        return match ($this) {
            self::Requested => Heroicon::Clock,
            self::InProgress => Heroicon::ArrowPath,
            self::Completed => Heroicon::CheckCircle,
            self::Cancelled => Heroicon::XCircle,
        };
    }
}
