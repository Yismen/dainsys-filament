<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByEmployeeSite
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('site')) {
            $builder->whereHas('employee', function ($employeeQuery)  {
                $employeeQuery->whereHas('site', function ($siteBuilder)  {
                    $siteBuilder
                        ->where('id', $this->request->input('site'))
                        ->orWhere('name', 'like', $this->request->input('site'));

                });
            });
        }

        return $next($builder);
    }
}
