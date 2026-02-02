<?php

namespace App\Jobs;

use App\Models\Holiday;
use App\Models\PayrollHour;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;

class DistributePayrollHoursJob implements ShouldQueue
{
    use Batchable, Queueable;

    public string $date;

    public string $employeeId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $date, string $employeeId)
    {
        $this->date = $date;
        $this->employeeId = $employeeId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $date = Carbon::parse($this->date);
        $startOfWeek = $date->clone()->startOfWeek();
        $endOfWeek = $date->clone()->endOfWeek();

        $payrollHours = PayrollHour::query()
            ->where('employee_id', $this->employeeId)
            ->whereDate('date', '>=', $startOfWeek)
            ->whereDate('date', '<=', $endOfWeek)
            ->orderBy('date', 'asc')
            ->get();

        if ($payrollHours->isEmpty()) {
            return;
        }

        $holidayDates = Holiday::query()
            ->whereDate('date', '>=', $startOfWeek)
            ->whereDate('date', '<=', $endOfWeek)
            ->get()
            ->map(fn (Holiday $holiday): string => Carbon::parse($holiday->date)->toDateString())
            ->flip();

        $workedDays = $payrollHours
            ->filter(fn (PayrollHour $payrollHour): bool => $payrollHour->total_hours > 0)
            ->count();

        $hasSevenDays = $workedDays === 7;

        foreach ($payrollHours as $payrollHour) {
            $dateString = $payrollHour->date->toDateString();
            $isHoliday = $holidayDates->has($dateString);
            $isSunday = $payrollHour->date->isSunday();

            $payrollHour->holiday_hours = 0;
            $payrollHour->seventh_day_hours = 0;
            $payrollHour->regular_hours = 0;
            $payrollHour->overtime_hours = 0;
            $payrollHour->is_holiday = $isHoliday;
            $payrollHour->is_sunday = $isSunday;

            if ($isHoliday) {
                $payrollHour->holiday_hours = $payrollHour->total_hours;
            } elseif ($isSunday && $hasSevenDays) {
                $payrollHour->seventh_day_hours = $payrollHour->total_hours;
            } else {
                $payrollHour->regular_hours = $payrollHour->total_hours;
            }

            $payrollHour->save();
        }

        $totalRegularHours = $payrollHours->sum('regular_hours');
        $excessHours = $totalRegularHours - 44;

        if ($excessHours <= 0) {
            return;
        }

        foreach ($payrollHours->sortByDesc('date') as $payrollHour) {
            if ($excessHours <= 0) {
                break;
            }

            if ($payrollHour->regular_hours <= 0) {
                continue;
            }

            $hoursToShift = min($payrollHour->regular_hours, $excessHours);
            $payrollHour->regular_hours -= $hoursToShift;
            $payrollHour->overtime_hours += $hoursToShift;
            $payrollHour->save();

            $excessHours -= $hoursToShift;
        }
    }
}
