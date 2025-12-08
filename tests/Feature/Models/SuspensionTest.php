<?php

use App\Models\Suspension;
use App\Events\SuspensionUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Testing\Fakes\EventFake;

test('suspensions model interacts with db table', function () {
    Mail::fake();

    $data = Suspension::factory()->make();

    Suspension::create($data->toArray());

    $this->assertDatabaseHas('suspensions', $data->only([
        'date', 'employee_id', 'suspension_reason_id', 'start_date', 'end_date', 'comments'
    ]));
});

test('suspension model uses soft delete', function () {
    Mail::fake();
    $suspension = Suspension::factory()->create();

    $suspension->delete();

    $this->assertSoftDeleted(Suspension::class, [
        'id' => $suspension->id
    ]);
});

test('suspensions model belongs to employee', function () {
    Mail::fake();
    $suspension = Suspension::factory()->create();

    expect($suspension->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('suspensions model belongs to suspension reason', function () {
    Mail::fake();
    $suspension = Suspension::factory()->create();

    expect($suspension->suspensionType())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('suspension model fires event when created', function () {
    Mail::fake();
    Event::fake();
    $suspension = Suspension::factory()->create();

    Event::assertDispatched(SuspensionUpdated::class);
});

/** @test */
// public function email_is_sent_when_suspension_is_created()
// {
//     Mail::fake();
//     Suspension::factory()->create();
//     Mail::assertQueued(MailSuspensionUpdated::class);
// }
