<?php

use App\Models\Downtime;

test('downtime model interacts with db table', function () {
    $data = Downtime::factory()->make();

    Downtime::create($data->toArray());

    $this->assertDatabaseHas('downtimes', $data->only([
        'date',
        'employee_id',
        'campaign_id',
        'downtime_reason_id',
        'time',
        'requester_id',
        'aprover_id',
        'converted_to_payroll_at',
    ]));
});

test('downtime model belongs to employee', function () {
    $downtime = Downtime::factory()
        ->has(\App\Models\Employee::factory(), 'employee')
        ->create();

    expect($downtime->employee)->toBeInstanceOf(\App\Models\Employee::class);
    expect($downtime->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('downtime model belongs to campaign', function () {
    $downtime = Downtime::factory()
        ->has(\App\Models\Campaign::factory(), 'campaign')
        ->create();

    expect($downtime->campaign)->toBeInstanceOf(\App\Models\Campaign::class);
    expect($downtime->campaign())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('downtime model belongs to downtime reason', function () {
    $downtime = Downtime::factory()
        ->has(\App\Models\DowntimeReason::factory(), 'downtimeReason')
        ->create();

    expect($downtime->downtimeReason)->toBeInstanceOf(\App\Models\DowntimeReason::class);
    expect($downtime->downtimeReason())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('downtime model belongs to requester', function () {
    $downtime = Downtime::factory()
        ->has(\App\Models\User::factory(), 'requester')
        ->create();

    expect($downtime->requester)->toBeInstanceOf(\App\Models\User::class);
    expect($downtime->requester())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('downtime model belongs to aprover', function () {
    $downtime = Downtime::factory()
        ->has(\App\Models\User::factory(), 'aprover')
        ->create();

    expect($downtime->aprover)->toBeInstanceOf(\App\Models\User::class);
    expect($downtime->aprover())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
