<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ByDate
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('date')) {
            $dates_range = \explode(
                separator: ',',
                string: $this->request->get('date'),
                limit: 2
            );

            $date_from = Carbon::parse(trim($dates_range[0]))->format('Y-m-d');
            $date_to = Carbon::parse(trim($dates_range[1] ?? $dates_range[0]))->format('Y-m-d');

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
