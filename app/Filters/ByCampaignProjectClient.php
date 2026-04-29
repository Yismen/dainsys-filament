<?php

namespace App\Filters;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ByCampaignProjectClient
{
    public function __construct(protected Request $request)
    {
        //
    }

    public function handle(Builder $builder, \Closure $next)
    {
        if ($this->request->has('client')) {
            $client = $this->request->input('client');

            $clientIds = Str::isUuid($client)
                ? [$client]
                : Client::query()
                    ->where('name', 'like', $client)
                    ->pluck('id');

            return $builder->whereHas('campaign.project.client', function (Builder $query) use ($clientIds): void {
                $query->whereIn('id', $clientIds);
            });
        }

        return $next($builder);
    }
}
