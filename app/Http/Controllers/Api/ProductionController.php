<?php

namespace App\Http\Controllers\Api;

use App\Filters\ByCampaign;
use App\Filters\ByCampaignProject;
use App\Filters\ByDate;
use App\Filters\ByEmployee;
use App\Filters\BySupervisor;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductionApiRequest;
use App\Http\Resources\ProductionResource;
use App\Models\Production;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Cache;

class ProductionController extends Controller
{
    #[QueryParameter('date', description: 'Date filter. Use YYYY-MM-DD, YYYY-MM-DD,YYYY-MM-DD, or last_N_days')]
    #[QueryParameter('campaign', description: 'ID or Name of the campaign to filter productions')]
    #[QueryParameter('project', description: 'ID or Name of the project to filter productions')]
    #[QueryParameter('employee', description: 'ID or Name of the employee to filter productions')]
    #[QueryParameter('supervisor', description: 'ID or Name of the supervisor to filter productions')]
    public function __invoke(ProductionApiRequest $request)
    {
        $query_filters = $request->uri()->query()->all();
        $class_string = \str(self::class)->replace('\\', ' ')->snake()->toString();
        $query_string = $request->getQueryString();
        $cache_key = $class_string.$query_string;

        $productions = Cache::rememberForever($cache_key, function () {
            $production = app(Pipeline::class)
                ->send(
                    Production::query()
                        ->with([
                            'campaign:id,name,source_id,project_id' => [
                                'source:id,name',
                                'project:id,name,client_id' => [
                                    'client:id,name',
                                ],
                            ],
                            'employee' => [
                                'site',
                            ],
                            'supervisor:id,name',
                        ])
                )
                ->through([
                    ByDate::class,
                    ByCampaign::class,
                    ByEmployee::class,
                    ByCampaignProject::class,
                    BySupervisor::class,
                    // \App\Filters\BySite::class,
                ])
                ->thenReturn()
                ->get();

            return $production;
        });

        return ProductionResource::collection($productions);
    }
}
