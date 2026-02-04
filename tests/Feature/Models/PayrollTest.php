<?php

use App\Models\Payroll;

it('interacts with db table', function () {
    $data = Payroll::factory()->make();

    Payroll::create($data->toArray());

    $this->assertDatabaseHas('payrolls', $data->only([
        'payable_date',
        'employee_id',

        'gross_income', // or payroll before deductions
        'taxable_payroll', // payroll after non-taxable deductions

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
        'net_payroll', // payroll after all deductions
    ]));
});

it('belongs to one employee', function () {
    $payroll = Payroll::factory()->create();

    expect($payroll->employee)->toBeInstanceOf(\App\Models\Employee::class);
    expect($payroll->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
