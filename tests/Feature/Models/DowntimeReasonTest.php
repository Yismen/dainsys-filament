<?php

use App\Models\Downtime;
use App\Models\DowntimeReason;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('downtime reasons model interacts with db table', function (): void {
    $data = DowntimeReason::factory()->make();

    DowntimeReason::create($data->toArray());

    $this->assertDatabaseHas('downtime_reasons', $data->only([
        'name', 'description',
    ]));
});

test('downtime reasons model has many downtimes', function (): void {
    $downtime_reason = DowntimeReason::factory()
        ->has(Downtime::factory(), 'downtimes')
        ->create();

    expect($downtime_reason->downtimes->first())->toBeInstanceOf(Downtime::class);
    expect($downtime_reason->downtimes())->toBeInstanceOf(HasMany::class);
});
