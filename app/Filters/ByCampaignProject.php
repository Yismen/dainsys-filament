<?php

namespace App\Filters;

use App\Models\Campaign;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ByCampaignProject
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('project')) {
            $project = (string) $this->request->input('project');

            $campaignIds = Campaign::query()
                ->when(
                    Str::isUuid($project),
                    fn (Builder $campaignQuery): Builder => $campaignQuery->where('project_id', $project),
                    function (Builder $campaignQuery) use ($project): Builder {
                        $projectIds = Project::query()
                            ->where('name', $project)
                            ->pluck('id');

                        return $campaignQuery->whereIn('project_id', $projectIds);
                    }
                )
                ->pluck('id');

            $builder->whereIn('campaign_id', $campaignIds);
        }

        return $next($builder);
    }
}
