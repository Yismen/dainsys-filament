<?php

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('interacts with db table', function (): void {
    $data = Payroll::factory()->make();

    Payroll::create($data->toArray());

    $this->assertDatabaseHas('payrolls', $data->only([
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
        'additional_incentives_1',
        'additional_incentives_2',
        'net_payroll',
        'total_payroll',
    ]));
});

it('belongs to one employee', function (): void {
    $payroll = Payroll::factory()->create();

    expect($payroll->employee)->toBeInstanceOf(Employee::class);
    expect($payroll->employee())->toBeInstanceOf(BelongsTo::class);
});
