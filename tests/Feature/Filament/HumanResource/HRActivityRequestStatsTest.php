<?php

use App\Enums\HRActivityRequestStatuses;
use App\Filament\HumanResource\Widgets\HRActivityRequestStats;
use App\Models\HRActivityRequest;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Mail::fake();
    Event::fake();
});

test('hr activity request stats widget displays correct counts', function () {
    // Create requests with different statuses
    HRActivityRequest::factory()->count(5)->create([
        'status' => HRActivityRequestStatuses::Requested,
    ]);

    HRActivityRequest::factory()->count(3)->create([
        'status' => HRActivityRequestStatuses::InProgress,
    ]);

    HRActivityRequest::factory()->count(2)->create([
        'status' => HRActivityRequestStatuses::Completed,
        'completed_at' => now(),
        'completion_comment' => 'Done',
    ]);

    livewire(HRActivityRequestStats::class)
        ->assertOk()
        ->assertSee('10') // Total
        ->assertSee('5') // Requested
        ->assertSee('3') // In Progress
        ->assertSee('2'); // Completed
});

test('hr activity request stats widget can be rendered', function () {
    HRActivityRequest::factory()->create([
        'status' => HRActivityRequestStatuses::Requested,
    ]);

    livewire(HRActivityRequestStats::class)
        ->assertOk()
        ->assertSee('HR Activity Requests');
});
