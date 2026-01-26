<?php

use App\Enums\HRActivityRequestStatuses;
use App\Models\HRActivityRequest;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Mail::fake();
    Event::fake();

    // Create role
    Role::firstOrCreate(['name' => 'Human Resource Manager']);

    $this->hrUser = User::factory()->create();
    $this->hrUser->assignRole('Human Resource Manager');

    $this->actingAs($this->hrUser);
});

test('hr can view list of activity requests', function () {
    $requests = HRActivityRequest::factory()->count(3)->create();

    livewire(\App\Filament\HumanResource\Resources\HRActivityRequests\Pages\ListHRActivityRequests::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords($requests);
});

test('hr can filter requests by status', function () {
    $requestedRequest = HRActivityRequest::factory()->create([
        'status' => HRActivityRequestStatuses::Requested,
    ]);

    $completedRequest = HRActivityRequest::factory()->create([
        'status' => HRActivityRequestStatuses::Completed,
        'completed_at' => now(),
        'completion_comment' => 'Done',
    ]);

    livewire(\App\Filament\HumanResource\Resources\HRActivityRequests\Pages\ListHRActivityRequests::class)
        ->filterTable('status', HRActivityRequestStatuses::Requested->value)
        ->assertCanSeeTableRecords([$requestedRequest])
        ->assertCanNotSeeTableRecords([$completedRequest]);
});

test('hr can view individual request', function () {
    $request = HRActivityRequest::factory()->create();

    livewire(\App\Filament\HumanResource\Resources\HRActivityRequests\Pages\ViewHRActivityRequest::class, ['record' => $request->id])
        ->assertSuccessful()
        ->assertSee($request->employee->full_name)
        ->assertSee($request->supervisor->full_name)
        ->assertSee($request->activity_type->value);
});

test('hr can complete a request with comment', function () {
    $request = HRActivityRequest::factory()->create([
        'status' => HRActivityRequestStatuses::Requested,
    ]);

    livewire(\App\Filament\HumanResource\Resources\HRActivityRequests\Pages\ListHRActivityRequests::class)
        ->callAction('complete', $request, data: [
            'comment' => 'Request completed successfully',
        ])
        ->assertNotified();

    $request->refresh();

    expect($request->status)->toBe(HRActivityRequestStatuses::Completed);
    expect($request->completion_comment)->toBe('Request completed successfully');
    expect($request->completed_at)->not->toBeNull();
});
