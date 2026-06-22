<?php

namespace App\Http\Controllers\Api;

use App\Filters\ByPayableDateRange;
use App\Http\Controllers\Controller;
use App\Http\Requests\IncentiveApiRequest;
use App\Http\Resources\IncentiveResource;
use App\Models\Incentive;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Pipeline\Pipeline;

class IncentiveController extends Controller
{
    #[QueryParameter('date', required: true, description: 'Date filter. Use YYYY-MM-DD, YYYY-MM-DD,YYYY-MM-DD, or last_N_days, or last_N_months')]
    #[QueryParameter('payable_date', required: true, description: 'Payable date filter. Use YYYY-MM-DD, YYYY-MM-DD,YYYY-MM-DD, or last_N_days, or last_N_months')]
    public function __invoke(IncentiveApiRequest $request)
    {
        $incentivesData = app(Pipeline::class)
            ->send(
                Incentive::query()
                    ->with(['employee:id,full_name', 'project:id,name'])
            )
            ->through([
                ByPayableDateRange::class,
            ])
            ->thenReturn()
            ->get();

        return IncentiveResource::collection($incentivesData);
    }
}
