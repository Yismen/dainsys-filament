<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByStatus
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('status')) {
            $status = $this->request->input('status');

            $builder->where('status', 'like', $status);
        }

        return $next($builder);
    }
}
