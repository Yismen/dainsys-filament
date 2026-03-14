<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ByPayrollEndingAt
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('payroll_ending_at')) {
            $datesRange = explode(',', (string) $this->request->input('payroll_ending_at'), 2);
            $dateFrom = Carbon::parse(trim($datesRange[0]))->format('Y-m-d');
            $dateTo = Carbon::parse(trim($datesRange[1] ?? $datesRange[0]))->format('Y-m-d');

            $builder->whereDate('payroll_ending_at', '>=', $dateFrom)
                ->whereDate('payroll_ending_at', '<=', $dateTo);
        }

        return $next($builder);
    }
}
