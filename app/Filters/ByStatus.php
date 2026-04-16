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
            $status = strtolower($this->request->input('status'));

            if ($status === 'active') {
                $status = 'hired';
            }

            if ($status === 'inactive') {
                $status = 'terminated';
            }

            $builder->when(
                $status === 'recents',
                fn (Builder $query) => $query->activesOrRecentlyTerminated(),
                fn (Builder $query) => $query->where('status', 'like', $status)
            );
        }

        return $next($builder);
    }
}
