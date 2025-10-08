<?php

use App\Models\TerminationType;

test('termination types model interacts with db table', function () {
    $data = TerminationType::factory()->make();

    TerminationType::create($data->toArray());

    $this->assertDatabaseHas('termination_types', $data->only([
        'name', 'description'
    ]));
});

test('termination type model uses soft delete', function () {
    $termination_type = TerminationType::factory()->create();

    $termination_type->delete();

    $this->assertSoftDeleted(TerminationType::class, [
        'id' => $termination_type->id
    ]);
});
