<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ByCampaign
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('campaign')) {
            $campaign = $this->request->input('campaign');

            $builder->whereHas(
                'campaign',
                function ($campaignBuilder) use ($campaign) {
                    $campaignBuilder
                        ->where('id', $campaign)
                        ->orWhere('name', 'like', $campaign);
                }
            );
        }

        return $next($builder);
    }
}
