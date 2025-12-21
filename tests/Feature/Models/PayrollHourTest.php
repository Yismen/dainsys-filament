<?php

use App\Models\PayrollHour;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;

test('downtime model interacts with db table', function () {
    Mail::fake();
    $data = PayrollHour::factory()->make();

    PayrollHour::create($data->toArray());

    $this->assertDatabaseHas('payroll_hours', $data->only([
        'date',
        // 'total_hours',
        'employee_id',
        // 'payroll_id',
        // 'week_ending
        'regular_hours',
        'nightly_hours',
        'overtime_hours',
        'holiday_hours',
        'day_off_hours',
    ]));
});

test('downtime model belongs to employee', function () {
    $downtime = PayrollHour::factory()->create();

    expect($downtime->employee)->toBeInstanceOf(\App\Models\Employee::class);
    expect($downtime->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

it('casts date attribute to date object', function () {
    $payrollHour = PayrollHour::factory()->create([
        'date' => '2025-08-15',
    ]);

    expect($payrollHour->date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    expect($payrollHour->date->toDateString())->toBe('2025-08-15');
});

it('casts week ending at attribute to date object', function () {
    $payrollHour = PayrollHour::factory()->create([
        'date' => '2025-12-17',
    ]);

    expect($payrollHour->week_ending_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    expect($payrollHour->week_ending_at->toDateString())->toBe('2025-12-21');
});

// it calculates total hours from components
it('calculates total hours from components minus nightly hours', function () {
    $data = [
        'regular_hours' => 8,
        'nightly_hours' => 2,
        'overtime_hours' => 3,
        'holiday_hours' => 4,
        'day_off_hours' => 1,
    ];

    $payrollHour = PayrollHour::factory()->create([
        'regular_hours' => 8,
        'nightly_hours' => 2,
        'overtime_hours' => 3,
        'holiday_hours' => 4,
        'day_off_hours' => 1,
    ]);

    unset($data['nightly_hours']);

    $expectedTotal = array_sum(array_values($data));

    expect($payrollHour->total_hours)->toBe($expectedTotal);
});

// it generate payroll id on update
it('generates payroll id to format YYYYMM15 on update when date is before or equal to the 15', function () {
    $payrollHour = PayrollHour::factory()->create([
        'payroll_id' => null,
        'date' => Carbon::parse('2025-08-13'),
    ]);

    expect($payrollHour->payroll_id)->toBe('PAYROLL-' . '20250815');

    $payrollHour->update(['date' => '2025-12-03']);

    expect($payrollHour->payroll_id)->toBe('PAYROLL-20251215');
});


it('generates payroll id to format YYYYMMEOM on update when date is afterthe 15', function () {
    $payrollHour = PayrollHour::factory()->create([
        'payroll_id' => null,
        'date' => Date::parse('2025-08-18'),
    ]);

    expect($payrollHour->payroll_id)->toBe('PAYROLL-' . '20250831');

    $payrollHour->update(['date' => '2025-12-21']);

    expect($payrollHour->payroll_id)->toBe('PAYROLL-20251231');
});

it('calculates the week ending field based on date', function () {
    $payrollHour = PayrollHour::factory()->create([
        'week_ending_at' => null,
        'date' => Date::parse('2025-12-11'),
    ]);

    expect($payrollHour->week_ending_at->format('Y-m-d'))->toBe('2025-12-14');

    $payrollHour->update(['date' => '2025-12-19']);

    expect($payrollHour->week_ending_at->format('Y-m-d'))->toBe('2025-12-21');
});
