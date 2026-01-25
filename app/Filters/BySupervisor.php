<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BySupervisor
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('supervisor')) {
            $supervisor = $this->request->get('supervisor');

            $builder->whereHas(
                'supervisor',
                function ($supervisorBuilder) use ($supervisor) {
                    $supervisorBuilder
                        ->where('id', $supervisor)
                        ->orWhere('name', 'like', $supervisor);
                }
            );
        }

        return $next($builder);
    }
}
