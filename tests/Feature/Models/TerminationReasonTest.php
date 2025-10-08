<?php

use App\Models\TerminationReason;

test('termination reasons model interacts with db table', function () {
    $data = TerminationReason::factory()->make();

    TerminationReason::create($data->toArray());

    $this->assertDatabaseHas('termination_reasons', $data->only([
        'name', 'description'
    ]));
});

test('termination reason model uses soft delete', function () {
    $termination_reason = TerminationReason::factory()->create();

    $termination_reason->delete();

    $this->assertSoftDeleted(TerminationReason::class, [
        'id' => $termination_reason->id
    ]);
});
