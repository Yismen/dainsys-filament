<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use InvalidArgumentException;

class DateFilterRangeService
{
    /**
     * @return array{0: string, 1: string}
     */
    public function resolve(string $value): array
    {
        $value = trim($value);

        $fixedRange = $this->resolveFixedRange($value);

        if ($fixedRange !== null) {
            return $fixedRange;
        }

        if (str_contains($value, '_')) {
            throw new InvalidArgumentException('Invalid fixed date range value.');
        }

        $datesRange = explode(',', $value, 2);
        $dateFrom = Carbon::parse(trim($datesRange[0]))->startOfDay();
        $dateTo = Carbon::parse(trim($datesRange[1] ?? $datesRange[0]))->startOfDay();

        if ($dateFrom->gt($dateTo)) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        return [$dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d')];
    }

    /**
     * @return array{0: string, 1: string}|null
     */
    protected function resolveFixedRange(string $value): ?array
    {
        if (! preg_match('/^last_([1-9]\d*)_days$/', trim($value), $matches)) {
            return null;
        }

        $days = (int) $matches[1];
        $dateTo = now()->startOfDay();
        $dateFrom = now()->startOfDay()->subDays($days - 1);

        return [$dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d')];
    }
}
