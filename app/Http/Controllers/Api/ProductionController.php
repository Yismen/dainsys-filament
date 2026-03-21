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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

class ProductionController extends Controller
{
    #[QueryParameter('date', description: 'Date filter. Use YYYY-MM-DD, YYYY-MM-DD,YYYY-MM-DD, or last_N_days')]
    #[QueryParameter('campaign', description: 'ID or Name of the campaign to filter productions')]
    #[QueryParameter('project', description: 'ID or Name of the project to filter productions')]
    #[QueryParameter('employee', description: 'ID or Name of the employee to filter productions')]
    #[QueryParameter('supervisor', description: 'ID or Name of the supervisor to filter productions')]
    public function __invoke(ProductionApiRequest $request)
    {
        $query = $this->productionsQuery();

        $productions = $query->get();

        return ProductionResource::collection($productions);
    }

    private function productionsQuery(): Builder
    {
        return app(Pipeline::class)
            ->send(
                Production::query()
                    ->select([
                        'id',
                        'unique_id',
                        'date',
                        'employee_id',
                        'campaign_id',
                        'supervisor_id',
                        'revenue_type',
                        'revenue_rate',
                        'revenue',
                        'sph_goal',
                        'conversions',
                        'total_time',
                        'production_time',
                        'talk_time',
                        'billable_time',
                    ])
                    ->with([
                        'campaign:id,name,source_id,project_id' => [
                            'source:id,name',
                            'project:id,name,client_id' => [
                                'client:id,name',
                            ],
                        ],
                        'employee:id,full_name,site_id' => [
                            'site:id,name',
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
            ])
            ->thenReturn();
    }
}
