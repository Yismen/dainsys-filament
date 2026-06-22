<?php

namespace App\Http\Controllers\Api;

use App\Filters\ByPayableDateRange;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollApiRequest;
use App\Http\Resources\PayrollResource;
use App\Models\Payroll;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Pipeline\Pipeline;

class PayrollController extends Controller
{
    #[QueryParameter('date',  required: true, description: 'Date filter. Use YYYY-MM-DD, YYYY-MM-DD,YYYY-MM-DD, or last_N_days, or last_N_months')]
    #[QueryParameter('payable_date',  required: true, description: 'Payable date filter. Use YYYY-MM-DD, YYYY-MM-DD,YYYY-MM-DD, or last_N_days, or last_N_months')]
    public function __invoke(PayrollApiRequest $request)
    {
        $payrollsData = app(Pipeline::class)
            ->send(
                Payroll::query()
                    ->with(['employee:id,full_name'])
            )
            ->through([
                ByPayableDateRange::class,
            ])
            ->thenReturn()
            ->get();

        return PayrollResource::collection($payrollsData);
    }
}
