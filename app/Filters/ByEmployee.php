<?php

namespace App\Filters;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ByEmployee
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('employee')) {
            $employee = (string) $this->request->input('employee');

            if (Str::isUuid($employee)) {
                $builder->where('employee_id', $employee);
            } else {
                $employeeIds = Employee::query()
                    ->where('full_name', $employee)
                    ->pluck('id');

                $builder->whereIn('employee_id', $employeeIds);
            }
        }

        return $next($builder);
    }
}
