<?php

namespace App\Filters;

use App\Services\DateFilterRangeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByDateRange
{
    public function __construct(protected Request $request, protected DateFilterRangeService $dateFilterRangeService) {}

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('date')) {
            [$dateFrom, $dateTo] = $this->dateFilterRangeService->resolve(
                value: (string) $this->request->input('date')
            );

            $builder->whereDate('date', '>=', $dateFrom)
                ->whereDate('date', '<=', $dateTo);
        }

        return $next($builder);
    }
}
