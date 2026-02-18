<?php

use App\Models\Disposition;

test('dispositions model interacts with db table', function (): void {
    $data = Disposition::factory()->make();

    Disposition::create($data->toArray());

    $this->assertDatabaseHas('dispositions', $data->only([
        'name', 'sales', 'description',
    ]));
});
