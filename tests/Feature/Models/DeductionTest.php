<?php

use App\Models\Deduction;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

it('interacts with db table', function (): void {
    $data = Deduction::factory()->make();

    Deduction::create($data->toArray());

    $this->assertDatabaseHas('deductions', [
        'employee_id' => $data->employee_id,
        'payable_date' => Carbon::parse($data->payable_date)->toDateString(),
        'amount' => $data->amount,
        'description' => $data->description,
    ]);
});

it('belongs to one employee', function (): void {
    $deduction = Deduction::factory()->create();

    expect($deduction->employee)->toBeInstanceOf(Employee::class);
    expect($deduction->employee())->toBeInstanceOf(BelongsTo::class);
});

it('casts payable date as date', function (): void {
    $deduction = Deduction::factory()->create();

    expect($deduction->payable_date)->toBeInstanceOf(Carbon::class);
});
