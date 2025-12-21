<?php

namespace App\Models;

use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollHour extends Model
{
    /** @use HasFactory<\Database\Factories\PayrollHourFactory> */
    use HasFactory;
    use SoftDeletes;
    use BelongsToEmployee;
    use HasUuids;

    protected $fillable = [
        'employee_id',
        'date',
        // 'total_hours',
        // 'payroll_id'
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
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (PayrollHour $payrollHour) {
            $payrollHour->total_hours = $payrollHour->calculateTotalHours();
            $payrollHour->payroll_id = $payrollHour->generatePayrollId();
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

    protected function generatePayrollId()
    {
        $date = $this->date;

        $date = $date->day <= 15 ?
            $date->startOfMonth()->addDays(14) :
            $date->endOfMonth();

        return 'PAYROLL-' . $date->format('Ymd');
    }
}
