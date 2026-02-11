<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByEmployeeSupervisor
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('supervisor')) {
            $supervisor = $this->request->input('supervisor');

            $builder->whereHas('employee', function ($employeeBuilder) use ($supervisor): void {
                $employeeBuilder->whereHas(
                    'supervisor',
                    function ($supervisorBuilder) use ($supervisor): void {
                        $supervisorBuilder
                            ->where('id', $supervisor)
                            ->orWhere('name', 'like', $supervisor);
                    }
                );
            });
        }

        return $next($builder);
    }
}
