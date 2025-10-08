<?php

use App\Models\DowntimeReason;
use Illuminate\Support\Facades\Mail;

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

    $this->assertSoftDeleted(DowntimeReason::class, $downtime_reason->only(['id']));
});

/** @test */
// public function downtime_reasons_model_has_many_performances()
// {
//     Mail::fake();
//     $downtime_reason = DowntimeReason::factory()->create();
//     $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $downtime_reason->performances());
// }
