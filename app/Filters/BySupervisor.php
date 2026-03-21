<?php

namespace App\Filters;

use App\Models\Supervisor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BySupervisor
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('supervisor')) {
            $supervisor = (string) $this->request->input('supervisor');

            if (Str::isUuid($supervisor)) {
                $builder->where('supervisor_id', $supervisor);
            } else {
                $supervisorIds = Supervisor::query()
                    ->where('name', $supervisor)
                    ->pluck('id');

                $builder->whereIn('supervisor_id', $supervisorIds);
            }
        }

        return $next($builder);
    }
}
