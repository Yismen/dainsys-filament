<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BySite
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if($this->request->has('site')) {
            $site = $this->request->get('site');

            // $builder->whereHas('site', function ($siteBuilder) use ($site) {
            //         $siteBuilder
            //             ->where('id', $site)
            //             ->orWhere('name', 'like', $site);

            //     });
            $builder->whereHas('employee', function ($employeeQuery) use ($site) {
                $employeeQuery->whereHas('site', function ($siteBuilder) use ($site) {
                    $siteBuilder
                        ->where('id', $site)
                        ->orWhere('name', 'like', $site);

                });
            });
        };

        return $next($builder);
    }
}
