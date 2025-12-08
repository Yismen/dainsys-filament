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

test('termination types model has many terminations', function () {
    $termination_type = TerminationType::factory()->create();

    expect($termination_type->terminations->first())->toBeInstanceOf(\App\Models\Termination::class);
    expect($termination_type->terminations())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('termination types model has many employees', function () {
    $termination_type = TerminationType::factory()->create();

    expect($termination_type->employees->first())->toBeInstanceOf(\App\Models\Employee::class);
    expect($termination_type->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
