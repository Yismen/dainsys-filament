<?php

use App\Models\Termination;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake([
        \App\Events\TerminationCreated::class,
    ]);
});


test('terminations model interacts with db table', function () {
    $data = Termination::factory()->make();

    Termination::create($data->toArray());

    $this->assertDatabaseHas('terminations', $data->only([
        // 'date',
        'employee_id',
        'termination_type',
        'is_rehireable',
    ]));
});

// it('casts date as date format Y-m-d', function () {
//     $termination = Termination::factory()->create(['date' => now()]);

//     dd($termination->date == now()->format('Y-m-d'));
// });

it('casts is_rehireable as boolean', function () {
    $termination = Termination::factory()->create(['is_rehireable' => 1]);

    expect($termination->is_rehireable)->toBeTrue();
});

test('terminations model belongs to employee', function () {
    $termination = Termination::factory()->create();

    expect($termination->employee)->toBeInstanceOf(\App\Models\Employee::class);
    expect($termination->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('termination model fires event when created', function () {
    $termination = Termination::factory()->create();

    Event::assertDispatched(\App\Events\TerminationCreated::class);
});

