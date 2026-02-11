<?php

use App\Models\Incentive;

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

    expect($incentive->employee)->toBeInstanceOf(\App\Models\Employee::class);
    expect($incentive->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
