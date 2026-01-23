<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ByEmployee
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if($this->request->has('employee')) {
            $employee = $this->request->get('employee');

            $builder->whereHas(
                'employee',
                function ($employeeBuilder) use ($employee) {
                    $employeeBuilder
                        ->where('id',  $employee)
                        ->orWhere('full_name', 'like', $employee);
                }
            );
        };

        return $next($builder);
    }
}
