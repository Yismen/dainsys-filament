<?php

use App\Models\DowntimeReason;
use Illuminate\Support\Facades\Mail;

use function Laravel\Prompts\text;

test('downtime reasons model interacts with db table', function () {
    Mail::fake();
    $data = DowntimeReason::factory()->make();

    DowntimeReason::create($data->toArray());

    $this->assertDatabaseHas('downtime_reasons', $data->only([
        'name'
    ]));
});

test('downtime reason model uses soft delete', function () {
    Mail::fake();
    $downtime_reason = DowntimeReason::factory()->create();

    $downtime_reason->delete();

    $this->assertSoftDeleted(DowntimeReason::class, [
        'id' => $downtime_reason->id
    ]);
});

test('downtime reasons model has many downtimes', function () {
    Mail::fake();
    $downtime_reason = DowntimeReason::factory()->create();

    expect($downtime_reason->downtimes->first())->toBeInstanceOf(\App\Models\Downtime::class);
    expect($downtime_reason->downtimes())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
