<?php

use App\Models\Universal;
use Illuminate\Support\Facades\Mail;

test('universals model interacts with db table', function () {
    Mail::fake();
    $data = Universal::factory()->make();

    Universal::create($data->toArray());

    $this->assertDatabaseHas('universals', $data->only([
        'employee_id', 'date_since', 'comments'
    ]));
});

test('universal model uses soft delete', function () {
    Mail::fake();
    $universal = Universal::factory()->create();

    $universal->delete();

    $this->assertSoftDeleted(Universal::class, $universal->only(['id']));
});

test('universals model belongs to employee', function () {
    Mail::fake();
    $universal = Universal::factory()->create();

    expect($universal->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
