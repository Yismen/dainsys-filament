<?php

use App\Models\Holiday;
use App\Models\PayrollHour;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;

test('downtime model interacts with db table', function () {
    Mail::fake();
    $data = PayrollHour::factory()->make();

    PayrollHour::create($data->toArray());

    $this->assertDatabaseHas('payroll_hours', $data->only([
        // 'date',
        'total_hours',
        'employee_id',
        // 'payroll_ending_at',
        // 'week_ending
        'nightly_hours',
        // 'regular_hours',
        // 'overtime_hours',
        // 'holiday_hours',
        // 'seventh_day_hours',
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

it('casts payroll ending at attribute to date object', function () {
    $payrollHour = PayrollHour::factory()->create([
        'date' => '2025-08-15',
    ]);

    expect($payrollHour->payroll_ending_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    expect($payrollHour->payroll_ending_at->toDateString())->toBe('2025-08-15');
});

it('casts week ending at attribute to date object', function () {
    $payrollHour = PayrollHour::factory()->create([
        'date' => '2025-12-17',
    ]);

    expect($payrollHour->week_ending_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    expect($payrollHour->week_ending_at->toDateString())->toBe('2025-12-21');
});

// it generate payroll id on update
it('generates payroll id to format YYYY-MM-15 on update when date is before or equal to the 15', function () {
    $payrollHour = PayrollHour::factory()->create([
        'payroll_ending_at' => null,
        'date' => Carbon::parse('2025-08-13'),
    ]);

    expect($payrollHour->payroll_ending_at->format('Y-m-d'))->toBe('2025-08-15');

    $payrollHour->update(['date' => '2025-12-03']);

    expect($payrollHour->payroll_ending_at->format('Y-m-d'))->toBe('2025-12-15');
});

it('generates payroll id to format YYYY-MM-EOM on update when date is after the 15', function () {
    $payrollHour = PayrollHour::factory()->create([
        'payroll_ending_at' => null,
        'date' => Date::parse('2025-08-18'),
    ]);

    expect($payrollHour->payroll_ending_at->format('Y-m-d'))->toBe('2025-08-31');

    $payrollHour->update(['date' => '2025-12-21']);

    expect($payrollHour->payroll_ending_at->format('Y-m-d'))->toBe('2025-12-31');
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

it('parses is_sunday attribute correctly', function () {
    $is_sunday = PayrollHour::factory()->create([
        'date' => Date::parse('2026-01-04'), // sunday
    ]);

    $not_sunday = PayrollHour::factory()->create([
        'date' => Date::parse('2026-01-08'), // Not sunday
    ]);

    expect($not_sunday->is_sunday)
        ->tobe(false);
    expect($is_sunday->is_sunday)
        ->tobe(true);
});

it('parses is_holiday attribute correctly', function () {
    Cache::flush();

    Holiday::factory()->create(['date' => '2026-01-04']);
    $holiday = PayrollHour::factory()->create([
        'date' => Date::parse('2026-01-04'), // holiday
    ]);

    $not_holiday = PayrollHour::factory()->create([
        'date' => Date::parse('2026-01-08'), // Not holiday
    ]);

    expect($not_holiday->is_holiday)
        ->tobe(false);
    expect($holiday->is_holiday)
        ->tobe(true);
});

it('parses holiday_hours correctly', function () {
    Holiday::factory()->create(['date' => '2026-01-04']);
    $is_holiday = PayrollHour::factory()->create([
        'date' => Date::parse('2026-01-04'), // holiday
        'total_hours' => 7,
    ]);

    $this->assertDatabaseHas('payroll_hours', [
        'id' => $is_holiday->id,
        'total_hours' => 7,
        'holiday_hours' => 7,
    ]);
});

it('parses non holiday_hours correctly', function () {
    Holiday::factory()->create(['date' => '2026-01-04']);
    $non_holiday = PayrollHour::factory()->create([
        'date' => Date::parse('2026-01-08'), // holiday
        'total_hours' => 7,
    ]);

    $this->assertDatabaseHas('payroll_hours', [
        'id' => $non_holiday->id,
        'total_hours' => 7,
        'holiday_hours' => 0,
    ]);
});
