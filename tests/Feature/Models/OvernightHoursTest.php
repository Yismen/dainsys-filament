<?php

use App\Models\OvernightHour;
use Illuminate\Support\Facades\Mail;

test('overnight hours model interacts with db table', function () {
    Mail::fake();
    $data = OvernightHour::factory()->make();

    OvernightHour::create($data->toArray());

    $this->assertDatabaseHas('overnight_hours', $data->only([
        'date', 'employee_id', 'hours'
    ]));
});

test('overnight hours model belongs to employee', function () {
    Mail::fake();
    $overnight_hour = OvernightHour::factory()->create();

    expect($overnight_hour->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
