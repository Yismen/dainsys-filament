<?php

use App\Models\Holiday;
use Illuminate\Support\Facades\Mail;

test('holidays model interacts with db table', function () {
    $data = Holiday::factory()->make();

    Holiday::create($data->toArray());

    $this->assertDatabaseHas('holidays', $data->only([
        'date', 'name', 'description'
    ]));
});
