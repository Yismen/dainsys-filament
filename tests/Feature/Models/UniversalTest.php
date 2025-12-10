<?php

use App\Models\Universal;
use Illuminate\Support\Facades\Mail;

test('universals model interacts with db table', function () {
    $data = Universal::factory()->make();

    Universal::create($data->toArray());

    $this->assertDatabaseHas('universals', $data->only([
        'employee_id', 'date_since'
    ]));
});

test('universals model belongs to employee', function () {
    $universal = Universal::factory()->create();

    expect($universal->employee)->toBeInstanceOf(\App\Models\Employee::class);
    expect($universal->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
