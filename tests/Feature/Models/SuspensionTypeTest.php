<?php

use App\Models\SuspensionType;

test('suspension types model interacts with db table', function () {
    $data = SuspensionType::factory()->make();

    SuspensionType::create($data->toArray());

    $this->assertDatabaseHas('suspension_types', $data->only([
        'name', 'description'
    ]));
});

test('suspension type model uses soft delete', function () {
    $suspension_type = SuspensionType::factory()->create();

    $suspension_type->delete();

    $this->assertSoftDeleted(SuspensionType::class, [
        'id' => $suspension_type->id
    ]);
});
