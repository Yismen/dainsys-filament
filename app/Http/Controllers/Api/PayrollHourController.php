<?php

namespace App\Http\Controllers\Api;

use App\Filters\ByDateRange;
use App\Filters\ByPayrollEndingAt;
use App\Filters\ByWeekEndingAt;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollHourApiRequest;
use App\Http\Resources\PayrollHourResource;
use App\Models\PayrollHour;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Pipeline\Pipeline;

class PayrollHourController extends Controller
{
    #[QueryParameter('date', required: true, description: 'Date filter. Use YYYY-MM-DD, YYYY-MM-DD,YYYY-MM-DD, or last_N_days, or last_N_months')]
    #[QueryParameter('week_ending_at', description: 'Week ending filter. Use YYYY-MM-DD', example: '2026-01-31')]
    #[QueryParameter('payroll_ending_at', description: 'Payroll ending filter. Use YYYY-MM-DD', example: '2026-01-31')]
    public function __invoke(PayrollHourApiRequest $request)
    {
        $payrollHoursData = app(Pipeline::class)
            ->send(
                PayrollHour::query()
                    ->with([
                        'employee:id,full_name',
                    ])
            )
            ->through([
                ByDateRange::class,
                ByWeekEndingAt::class,
                ByPayrollEndingAt::class,
            ])
            ->thenReturn()
            ->get();

        return PayrollHourResource::collection($payrollHoursData);
    }
}
