<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum InvoiceStatuses: string implements HasLabel
{
    case Pending = 'pending';
    case PartiallyPaid = 'partially_paid';
    case Paid = 'paid';
    case Overdue = 'overdue';
    case Cancelled = 'cancelled';

    /**
     * Get the color associated with each status.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::PartiallyPaid => 'info',
            self::Paid => 'success',
            self::Overdue => 'danger',
            self::Cancelled => 'gray',
        };
    }

    public function getTextColor()
    {
        return match ($this) {
            self::Pending => 'rgb(211, 84, 0)',
            self::PartiallyPaid => 'rgb(13, 71, 161)',
            self::Paid => 'rgb(22, 160, 133)',
            self::Overdue => 'rgb(192, 57, 43)',
            self::Cancelled => 'rgb(127, 140, 141)',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => __('enums.invoice_status.pending'),
            self::PartiallyPaid => __('enums.invoice_status.partially_paid'),
            self::Paid => __('enums.invoice_status.paid'),
            self::Overdue => __('enums.invoice_status.overdue'),
            self::Cancelled => __('enums.invoice_status.cancelled'),
        };
    }

    /**
     * Get all names of the statuses.
     */
    public static function getNames(): array
    {
        return array_map(fn ($status) => $status->name, self::cases());
    }

    /**
     * Get all values of the statuses.
     */
    public static function getValues(): array
    {
        return array_map(fn ($status) => $status->value, self::cases());
    }

    public static function toArray(): array
    {
        $array = [];

        foreach (self::cases() as $status) {
            $array[$status->value] = $status->getLabel();
        }

        return $array;
    }

    public static function itemsFromArray(array $statuses): array
    {
        $statusesArray = [];

        foreach ($statuses as $status) {
            if (is_string($status)) {
                $status = self::from($status);
            }
            if ($status instanceof self) {
                $statusesArray[$status->value] = $status->getLabel();
            }
        }

        return $statusesArray;
    }
}
