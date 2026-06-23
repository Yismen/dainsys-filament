<?php

namespace App\Filters;

use App\Services\DateFilterRangeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ByDate
{
    public function __construct(protected Request $request, protected DateFilterRangeService $dateFilterRangeService) {}

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('date')) {
            [$date_from, $date_to] = $this->dateFilterRangeService->resolve(
                value: (string) $this->request->input('date')
            );

            $builder->where('date', '>=', $date_from)
                ->where('date', '<', Carbon::parse($date_to)->addDay()->format('Y-m-d'));
        }

        return $next($builder);
    }
}
