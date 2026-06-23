<?php

namespace App\Filters;

use App\Services\DateFilterRangeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ByWeekEndingAt
{
    public function __construct(protected Request $request, protected DateFilterRangeService $dateFilterRangeService) {}

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('week_ending_at')) {
            [$dateFrom, $dateTo] = $this->dateFilterRangeService->resolve(
                value: (string) $this->request->input('week_ending_at')
            );

            $builder->where('week_ending_at', '>=', $dateFrom)
                ->where('week_ending_at', '<', Carbon::parse($dateTo)->addDay()->format('Y-m-d'));
        }

        return $next($builder);
    }
}
