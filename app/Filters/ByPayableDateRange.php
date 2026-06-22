<?php

namespace App\Filters;

use App\Services\DateFilterRangeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByPayableDateRange
{
    public function __construct(protected Request $request, protected DateFilterRangeService $dateFilterRangeService) {}

    public function handle(Builder $builder, \Closure $next)
    {
        $dateValue = $this->request->input('date') ?? $this->request->input('payable_date');

        if ($dateValue !== null) {
            [$dateFrom, $dateTo] = $this->dateFilterRangeService->resolve(
                value: (string) $dateValue
            );

            $builder->whereDate('payable_date', '>=', $dateFrom)
                ->whereDate('payable_date', '<=', $dateTo);
        }

        return $next($builder);
    }
}
