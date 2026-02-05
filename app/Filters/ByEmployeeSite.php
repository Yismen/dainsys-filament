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
            $site = $this->request->get('site');

            $builder->whereHas('site', function ($siteBuilder) use ($site) {
                $siteBuilder
                    ->where('id', $site)
                    ->orWhere('name', 'like', $site);

            });
        }

        return $next($builder);
    }
}
