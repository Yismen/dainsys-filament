<?php

namespace App\Http\Controllers\Api;

use App\Models\Production;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\ProductionResource;
use App\Http\Requests\ProductionApiRequest;

class ProductionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ProductionApiRequest $request)
    {
        $query_filters = $request->uri()->query()->all();
        $class_string = \str(self::class)->replace('\\', ' ')->snake()->toString();
        $query_string = $request->getQueryString();
        $cache_key = $class_string . $query_string;

        $productions = Cache::rememberForever($cache_key, function () use ($query_filters) {
            $production = app(Pipeline::class)
                ->send(
                    Production::query()
                        ->with([
                            'campaign:id,name,source_id,project_id' => [
                                'source:id,name',
                                'project:id,name,client_id' => [
                                    'client:id,name'
                                ],
                            ],
                            'employee' => [
                                'site',
                            ],
                            'supervisor:id,name',
                        ])
                )
                ->through([
                    \App\Filters\ByDate::class,
                    \App\Filters\ByCampaign::class,
                    \App\Filters\ByProject::class,
                    \App\Filters\ByEmployee::class,
                    \App\Filters\BySupervisor::class,
                    // \App\Filters\BySite::class,
                ])
                ->thenReturn()
                ->get();

            return $production;
        });

        return ProductionResource::collection($productions);
    }
}
