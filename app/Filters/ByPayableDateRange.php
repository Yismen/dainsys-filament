<?php

namespace App\Filters;

use App\Services\DateFilterRangeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

            $builder->where('payable_date', '>=', $dateFrom)
                ->where('payable_date', '<', Carbon::parse($dateTo)->addDay()->format('Y-m-d'));
        }

        return $next($builder);
    }
}
