<?php

use App\Models\Employee;
use App\Models\Universal;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('universals model interacts with db table', function (): void {
    $data = Universal::factory()->make();

    Universal::create($data->toArray());

    $this->assertDatabaseHas('universals', $data->only([
        'employee_id', 'date_since',
    ]));
});

test('universals model belongs to employee', function (): void {
    $universal = Universal::factory()->create();

    expect($universal->employee)->toBeInstanceOf(Employee::class);
    expect($universal->employee())->toBeInstanceOf(BelongsTo::class);
});
