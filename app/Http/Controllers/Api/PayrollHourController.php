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
use Illuminate\Support\Facades\Cache;

class PayrollHourController extends Controller
{
    #[QueryParameter('date', description: 'Date filter. Use YYYY-MM-DD, YYYY-MM-DD,YYYY-MM-DD, or last_N_days')]
    #[QueryParameter('week_ending_at', description: 'Week ending filter. Use YYYY-MM-DD, YYYY-MM-DD,YYYY-MM-DD, or last_N_days')]
    #[QueryParameter('payroll_ending_at', description: 'Payroll ending filter. Use YYYY-MM-DD, YYYY-MM-DD,YYYY-MM-DD, or last_N_days')]
    public function __invoke(PayrollHourApiRequest $request)
    {
        $queryFilters = $request->uri()->query()->all();
        $classString = str(self::class)->replace('\\', ' ')->snake()->toString();
        $queryString = $request->getQueryString();
        $cacheKey = $classString.$queryString;

        $payrollHours = Cache::rememberForever($cacheKey, function () {
            return app(Pipeline::class)
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
        });

        return PayrollHourResource::collection($payrollHours);
    }
}
