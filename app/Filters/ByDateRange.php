<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ByDateRange
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('date')) {
            $datesRange = explode(',', (string) $this->request->input('date'), 2);
            $dateFrom = Carbon::parse(trim($datesRange[0]))->format('Y-m-d');
            $dateTo = Carbon::parse(trim($datesRange[1] ?? $datesRange[0]))->format('Y-m-d');

            $builder->whereDate('date', '>=', $dateFrom)
                ->whereDate('date', '<=', $dateTo);
        }

        return $next($builder);
    }
}
