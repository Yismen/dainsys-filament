<?php

use App\Models\Termination;
use App\Events\TerminationCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


test('terminations model interacts with db table', function () {
    Mail::fake();
    $data = Termination::factory()->make();

    Termination::create($data->toArray());

    $this->assertDatabaseHas('terminations', $data->only([
        'employee_id', 'termination_type_id', 'termination_reason_id', 'comments', 'rehireable'
    ]));
});

test('termination model uses soft delete', function () {
    Mail::fake();
    $termination = Termination::factory()->create();

    $termination->delete();

    $this->assertSoftDeleted(Termination::class, [
        'id' => $termination->id
    ]);
});

test('terminations model belongs to employee', function () {
    Mail::fake();
    $termination = Termination::factory()->create();

    expect($termination->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('terminations model belongs to termination type', function () {
    Mail::fake();
    $termination = Termination::factory()->create();

    expect($termination->terminationType())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('terminations model belongs to termination reason', function () {
    Mail::fake();
    $termination = Termination::factory()->create();

    expect($termination->terminationReason())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('termination model fires event when created', function () {
    Mail::fake();
    Event::fake();
    $termination = Termination::factory()->create();

    Event::assertDispatched(TerminationCreated::class);
});

/** @test */
// public function email_is_sent_when_termination_is_created()
// {
//     Mail::fake();
//     Termination::factory()->create();
//     Mail::assertQueued(MailTerminationCreated::class);
// }
