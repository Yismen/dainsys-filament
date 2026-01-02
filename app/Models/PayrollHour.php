<?php

namespace App\Models;

use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class PayrollHour extends \App\Models\BaseModels\AppModel
{
    use BelongsToEmployee;

    /** @use HasFactory<\Database\Factories\PayrollHourFactory> */
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'date',
        // 'total_hours',
        // 'payroll_ending_at'
        // 'week_ending_at'
        'regular_hours',
        'nightly_hours',
        'overtime_hours',
        'holiday_hours',
        'day_off_hours',
        'converted_to_payroll_at',
    ];

    protected $casts = [
        'date' => 'date',
        'week_ending_at' => 'date',
        'payroll_ending_at' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (PayrollHour $payrollHour) {
            $payrollHour->total_hours = $payrollHour->calculateTotalHours();
            $payrollHour->payroll_ending_at = $payrollHour->generatePayrollId();
            $payrollHour->week_ending_at = $payrollHour->date->copy()->endOfWeek();

            $payrollHour->saveQuietly();
        });
    }

    protected function calculateTotalHours()
    {
        return $this->regular_hours
            // + $this->nightly_hours
            + $this->overtime_hours
            + $this->holiday_hours
            + $this->day_off_hours;
    }

    protected function generatePayrollId(): Carbon
    {
        $date = $this->date;

        return $date->day <= 15 ?
            $date->startOfMonth()->addDays(14) :
            $date->endOfMonth();

    }
}
