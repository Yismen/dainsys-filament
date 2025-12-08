<?php

use App\Models\Campaign;
use App\Enums\RevenueTypes;
use App\Models\Downtime;
use Illuminate\Support\Facades\Mail;

test('downtime model interacts with db table', function () {
    Mail::fake();
    $data = Downtime::factory()->make();

    Downtime::create($data->toArray());

    $this->assertDatabaseHas('downtimes', $data->only(['date', 'employee_id', 'campaign_id', 'time', 'downtime_reason_id', 'reporter_id',
    ]));
});

test('downtime model uses soft delete', function () {
    $downtime = Downtime::factory()->create();

    $downtime->delete();

    $this->assertSoftDeleted(Downtime::class, [
        'id' => $downtime->id
    ]);
});

test('downtime model belongs to employee', function () {
    $downtime = Downtime::factory()->create();

    expect($downtime->employee)->toBeInstanceOf(\App\Models\Employee::class);
    expect($downtime->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('downtime model belongs to campaign', function () {
    $downtime = Downtime::factory()->create();

    expect($downtime->campaign)->toBeInstanceOf(\App\Models\Campaign::class);
    expect($downtime->campaign())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('downtime model belongs to downtime reason', function () {
    $downtime = Downtime::factory()->create();

    expect($downtime->downtimeReason)->toBeInstanceOf(\App\Models\DowntimeReason::class);
    expect($downtime->downtimeReason())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('downtime model belongs to reporter', function () {
    $downtime = Downtime::factory()->create();

    expect($downtime->reporter)->toBeInstanceOf(\App\Models\User::class);
    expect($downtime->reporter())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
