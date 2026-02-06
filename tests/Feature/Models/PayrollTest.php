<?php

use App\Models\Payroll;

it('interacts with db table', function () {
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

it('belongs to one employee', function () {
    $payroll = Payroll::factory()->create();

    expect($payroll->employee)->toBeInstanceOf(\App\Models\Employee::class);
    expect($payroll->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
