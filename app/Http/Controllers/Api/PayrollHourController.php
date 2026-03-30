<?php

namespace App\Http\Controllers\Api;

use App\Filters\ByDateRange;
use App\Filters\ByPayrollEndingAt;
use App\Filters\ByWeekEndingAt;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollHourApiRequest;
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

        $payrollHoursData = Cache::rememberForever($cacheKey, function () {
            $payrollHours = app(Pipeline::class)
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
                ->get()
                ->map(fn ($payrollHour): array => [
                    'id' => (int) $payrollHour->id,
                    'employee_id' => (int) $payrollHour->employee_id,
                    'employee_full_name' => (string) $payrollHour->employee->full_name,
                    'date' => $payrollHour->date->format('Y-m-d'),
                    'total_hours' => (float) $payrollHour->total_hours,
                    'regular_hours' => (float) $payrollHour->regular_hours,
                    'overtime_hours' => (float) $payrollHour->overtime_hours,
                    'holiday_hours' => (float) $payrollHour->holiday_hours,
                    'seventh_day_hours' => (float) $payrollHour->seventh_day_hours,
                    'week_ending_at' => $payrollHour->week_ending_at?->format('Y-m-d'),
                    'payroll_ending_at' => $payrollHour->payroll_ending_at?->format('Y-m-d'),
                    'is_sunday' => (bool) $payrollHour->is_sunday,
                    'is_holiday' => (bool) $payrollHour->is_holiday,
                ])
                ->values()
                ->all();

            return ['data' => $payrollHours];
        });

        return response()->json($payrollHoursData);
    }
}
