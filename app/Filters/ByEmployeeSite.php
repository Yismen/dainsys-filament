<?php

namespace App\Filters;

use App\Models\Site;
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
            $site = $this->request->input('site');

            $siteIds = Site::where('id', $site)
                ->orWhere('name', 'like', $site)
                ->pluck('id');

            $builder->whereHas('employee', function ($employeeQuery) use ($siteIds): void {
                $employeeQuery->whereHas('site', function ($siteBuilder) use ($siteIds): void {
                    $siteBuilder->whereIn('id', $siteIds);
                });
            });
        }

        return $next($builder);
    }
}
