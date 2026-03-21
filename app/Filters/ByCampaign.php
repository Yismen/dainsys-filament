<?php

namespace App\Filters;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ByCampaign
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('campaign')) {
            $campaign = (string) $this->request->input('campaign');

            if (Str::isUuid($campaign)) {
                $builder->where('campaign_id', $campaign);
            } else {
                $campaignIds = Campaign::query()
                    ->where('name', $campaign)
                    ->pluck('id');

                $builder->whereIn('campaign_id', $campaignIds);
            }
        }

        return $next($builder);
    }
}
