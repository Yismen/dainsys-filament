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
        'regular_hours',
        'nightly_hours',
        'overtime_hours',
        'holiday_hours',
        'day_off_hours',
        'converted_to_payroll_at',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (PayrollHour $payrollHour) {
            $payrollHour->total_hours = $payrollHour->calculateTotalHours();
            $payrollHour->payroll_id = $payrollHour->generatePayrollId();

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

        return 'PAYROLL-' . $date->endOfMonth()->format('Ymd');
    }
}
