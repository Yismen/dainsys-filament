<?php

use App\Models\SuspensionReason;

test('suspension reasons model interacts with db table', function () {
    $data = SuspensionReason::factory()->make();

    SuspensionReason::create($data->toArray());

    $this->assertDatabaseHas('suspension_reasons', $data->only([
        'name', 'description'
    ]));
});

test('suspension reason model uses soft delete', function () {
    $suspension_reason = SuspensionReason::factory()->create();

    $suspension_reason->delete();

    $this->assertSoftDeleted(SuspensionReason::class, [
        'id' => $suspension_reason->id
    ]);
});

test('suspension reasons model has many suspensions', function () {
    $suspension_reason = SuspensionReason::factory()->create();

    expect($suspension_reason->suspensions->first())->toBeInstanceOf(\App\Models\Suspension::class);
    expect($suspension_reason->suspensions())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
