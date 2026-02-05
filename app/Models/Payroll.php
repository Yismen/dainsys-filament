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
        'salary_rate',
        'total_hours',
        'salary_income',
        'medical_licence',
        'gross_income',
        'deduction_ars',
        'deduction_afp',
        'deductions_other',
        'total_deductions',
        'nightly_incomes',
        'overtime_incomes',
        'holiday_incomes',
        'additional_incentives_2',
        'additional_incentives_1',
        'net_payroll',
        'total_payroll',
    ];

    protected $casts = [
        'payable_date' => 'date:Y-m-d',
        'salary_rate' => 'decimal:2',
        'total_hours' => 'decimal:2',
        'salary_income' => 'decimal:2',
        'medical_licence' => 'decimal:2',
        'gross_income' => 'decimal:2',
        'deduction_ars' => 'decimal:2',
        'deduction_afp' => 'decimal:2',
        'deductions_other' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'nightly_incomes' => 'decimal:2',
        'overtime_incomes' => 'decimal:2',
        'holiday_incomes' => 'decimal:2',
        'additional_incentives_1' => 'decimal:2',
        'additional_incentives_2' => 'decimal:2',
        'net_payroll' => 'decimal:2',
        'total_payroll' => 'decimal:2',
    ];

    public function getPayableDateAttribute($value): string
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
