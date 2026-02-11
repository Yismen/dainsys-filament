<?php

use App\Enums\HRActivityRequestStatuses;
use App\Events\HRActivityRequestCompleted;
use App\Events\HRActivityRequestCreated;
use App\Models\HRActivityRequest;
use Illuminate\Support\Facades\Event;

test('hr activity request created event is dispatched', function (): void {
    Event::fake();

    HRActivityRequest::factory()->create();

    Event::assertDispatched(HRActivityRequestCreated::class);
});

test('hr activity request completed event is dispatched when marked as completed', function (): void {
    Event::fake();

    $request = HRActivityRequest::factory()->create();

    $request->markAsCompleted('Test completion comment');

    Event::assertDispatched(HRActivityRequestCompleted::class);
});

test('marking request as completed updates status and timestamps', function (): void {
    Event::fake(); // Fake all events to prevent listeners from querying non-existent roles

    $request = HRActivityRequest::factory()->create([
        'status' => HRActivityRequestStatuses::Requested,
    ]);

    expect($request->status)->toBe(HRActivityRequestStatuses::Requested);
    expect($request->completed_at)->toBeNull();
    expect($request->completion_comment)->toBeNull();

    $request->markAsCompleted('Task completed successfully');

    $request->refresh();

    expect($request->status)->toBe(HRActivityRequestStatuses::Completed);
    expect($request->completed_at)->not->toBeNull();
    expect($request->completion_comment)->toBe('Task completed successfully');
});
