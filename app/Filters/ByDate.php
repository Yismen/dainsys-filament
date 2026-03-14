<?php

namespace App\Filters;

use App\Services\DateFilterRangeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByDate
{
    public function __construct(protected Request $request, protected DateFilterRangeService $dateFilterRangeService) {}

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('date')) {
            [$date_from, $date_to] = $this->dateFilterRangeService->resolve(
                value: (string) $this->request->input('date')
            );

            $builder->whereDate(
                column: 'date',
                operator: '>=',
                value: $date_from
            )
                ->whereDate(
                    column: 'date',
                    operator: '<=',
                    value: $date_to
                );
        }

        return $next($builder);
    }
}
