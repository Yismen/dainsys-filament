<?php

use App\Models\Employee;
use App\Models\Incentive;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('interacts with db table', function (): void {
    $data = Incentive::factory()->make();

    Incentive::create($data->toArray());

    $this->assertDatabaseHas('incentives', $data->only([
        'payable_date',
        'employee_id',
        'project_id',
        'total_production_hours',
        'total_sales',
        'amount',
        'notes',
    ]));
});

it('belongs to one employee', function (): void {
    $incentive = Incentive::factory()->create();

    expect($incentive->employee)->toBeInstanceOf(Employee::class);
    expect($incentive->employee())->toBeInstanceOf(BelongsTo::class);
});
