<?php

namespace App\Models;

use App\Models\Traits\BelongsToEmployee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Payroll extends \App\Models\BaseModels\AppModel
{
    use BelongsToEmployee;

    /** @use HasFactory<\Database\Factories\PayrollFactory> */
    use HasFactory;

    protected $fillable = [
        'payable_date',
        'employee_id',
        'gross_income',
        'taxable_payroll',
        'hourly_rate',
        'regular_hours',
        'overtime_hours',
        'holiday_hours',
        'night_shift_hours',
        'additional_incentives_1',
        'additional_incentives_2',
        'deduction_afp',
        'deduction_ars',
        'other_deductions',
        'net_payroll',
    ];

    protected $casts = [
        'payable_date' => 'date:Y-m-d',
        'gross_income' => 'decimal:2',
        'taxable_payroll' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'regular_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'holiday_hours' => 'decimal:2',
        'night_shift_hours' => 'decimal:2',
        'additional_incentives_1' => 'decimal:2',
        'additional_incentives_2' => 'decimal:2',
        'deduction_afp' => 'decimal:2',
        'deduction_ars' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'net_payroll' => 'decimal:2',
    ];

    public function getPayableDateAttribute($value): string
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
