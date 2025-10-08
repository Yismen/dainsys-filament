<?php

use App\Models\Holiday;
use Illuminate\Support\Facades\Mail;

test('holidays model interacts with db table', function () {
    Mail::fake();
    $data = Holiday::factory()->make();

    Holiday::create($data->toArray());

    $this->assertDatabaseHas('holidays', $data->only([
        'name', 'date', 'description'
    ]));
});

test('holiday model uses soft delete', function () {
    Mail::fake();
    $holiday = Holiday::factory()->create();

    $holiday->delete();

    $this->assertSoftDeleted(Holiday::class, [
        'id' => $holiday->id
    ]);
});
