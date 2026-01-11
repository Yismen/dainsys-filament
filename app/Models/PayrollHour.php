<?php

namespace App\Models;

use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class PayrollHour extends \App\Models\BaseModels\AppModel
{
    use BelongsToEmployee;

    /** @use HasFactory<\Database\Factories\PayrollHourFactory> */
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'date',
        'total_hours',
        // 'payroll_ending_at'
        // 'week_ending_at'
        // 'is_sunday'
        // 'is_holiday'
        'nightly_hours',
        // 'regular_hours',
        // 'overtime_hours',
        // 'holiday_hours',
        // 'seventh_day_hours',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'week_ending_at' => 'date',
        'payroll_ending_at' => 'date',
        'is_sunday' => 'bool',
        'is_holiday' => 'bool',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (PayrollHour $payrollHour) {
            $isHoliday = Cache::remember('date_'.$payrollHour->date->format('Y-m-d').'is_a_holiday', now()->addHour(), function () use ($payrollHour) {
                return Holiday::query()->whereDate('date', $payrollHour->date)->exists();
            });

            $payrollHour->payroll_ending_at = $payrollHour->generatePayrollId();
            $payrollHour->week_ending_at = $payrollHour->date->copy()->endOfWeek();
            $payrollHour->is_sunday = $payrollHour->date->isSunday();
            $payrollHour->is_holiday = false;

            if ($isHoliday) {
                $payrollHour->is_holiday = true;
                $payrollHour->regular_hours = 0;
                $payrollHour->holiday_hours = $payrollHour->total_hours;
            }

            $payrollHour->saveQuietly();
        });
    }

    protected function generatePayrollId(): Carbon
    {
        $date = $this->date;

        return $date->day <= 15 ?
            $date->startOfMonth()->addDays(14) :
            $date->endOfMonth();

    }
}
