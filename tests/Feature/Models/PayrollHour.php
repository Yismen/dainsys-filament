<?php

use App\Models\PayrollHour;
use Illuminate\Support\Facades\Mail;

test('downtime model interacts with db table', function () {
    Mail::fake();
    $data = PayrollHour::factory()->make();

    PayrollHour::create($data->toArray());

    $this->assertDatabaseHas('payroll_hours', $data->only(['date', 'employee_id', 'time', 'regular_hours', 'nightly_hours', 'overtime_hours', 'holiday_hours', 'days_off_hours',
    ]));
});

test('downtime model uses soft delete', function () {
    $downtime = PayrollHour::factory()->create();

    $downtime->delete();

    $this->assertSoftDeleted(PayrollHour::class, [
        'id' => $downtime->id
    ]);
});

test('downtime model belongs to employee', function () {
    $downtime = PayrollHour::factory()->create();

    expect($downtime->employee)->toBeInstanceOf(\App\Models\Employee::class);
    expect($downtime->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
