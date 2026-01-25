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
            $project = $this->request->get('project');

            $builder->whereHas('campaign', function ($campaignQuery) use ($project) {
                $campaignQuery->whereHas('project', function ($projectBuilder) use ($project) {
                    $projectBuilder
                        ->where('id', $project)
                        ->orWhere('name', 'like', $project);

                });
            });
        }

        return $next($builder);
    }
}
