<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByProject
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('project')) {
            $project = $this->request->input('project');

            $builder->whereHas('project', function ($projectBuilder) use ($project): void {
                $projectBuilder
                    ->where('id', $project)
                    ->orWhere('name', 'like', $project);

            });
        }

        return $next($builder);
    }
}
