<?php

use App\Models\DowntimeReason;

test('downtime reasons model interacts with db table', function (): void {
    $data = DowntimeReason::factory()->make();

    DowntimeReason::create($data->toArray());

    $this->assertDatabaseHas('downtime_reasons', $data->only([
        'name', 'description',
    ]));
});

test('downtime reasons model has many downtimes', function (): void {
    $downtime_reason = DowntimeReason::factory()
        ->has(\App\Models\Downtime::factory(), 'downtimes')
        ->create();

    expect($downtime_reason->downtimes->first())->toBeInstanceOf(\App\Models\Downtime::class);
    expect($downtime_reason->downtimes())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
