<?php

use App\Models\Suspension;
use App\Events\SuspensionUpdated;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake([
        SuspensionUpdated::class,
    ]);
});

test('suspensions model interacts with db table', function () {

    $data = Suspension::factory()->make();

    Suspension::create($data->toArray());

    $this->assertDatabaseHas('suspensions', $data->only([
        'employee_id',
        'suspension_type_id',
        // 'starts_at',
        // 'ends_at'
    ]));
});

test('suspensions model belongs to employee', function () {
    $suspension = Suspension::factory()->create();

    expect($suspension->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('suspensions model belongs to suspension type', function () {
    $suspension = Suspension::factory()->create();

    expect($suspension->suspensionType())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('suspension model fires event when created', function () {
    $suspension = Suspension::factory()->create();

    Event::assertDispatched(SuspensionUpdated::class);
});

it('casts fields as date', function ($field) {
    $suspension = Suspension::factory()->create();

    expect($suspension->$field)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
})->with([
    'starts_at',
    'ends_at',
]);
