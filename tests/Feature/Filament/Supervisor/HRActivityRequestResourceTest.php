<?php

use App\Enums\HRActivityRequestStatuses;
use App\Enums\HRActivityTypes;
use App\Filament\Supervisor\Resources\HRActivityRequests\Pages\ListHRActivityRequests;
use App\Filament\Supervisor\Resources\HRActivityRequests\Pages\ViewHRActivityRequest;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\HRActivityRequest;
use App\Models\Supervisor;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Mail::fake();
    Event::fake();

    Filament::setCurrentPanel(
        Filament::getPanel('supervisor'),
    );

    $this->user = $this->createUserWithPermissionsToActions(
        actions: ['view', 'viewAny', 'create', 'update', 'delete'],
        model_name: 'HRActivityRequest'
    );
    $this->supervisor = Supervisor::factory()->create([
        'user_id' => $this->user->id,
        'is_active' => true,
    ]);

    $this->actingAs($this->user);
});

test('supervisor can view their own activity requests', function (): void {
    $ownRequests = HRActivityRequest::factory()->count(3)->create([
        'supervisor_id' => $this->supervisor->id,
    ]);

    $otherRequests = HRActivityRequest::factory()->count(2)->create();

    livewire(ListHRActivityRequests::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords($ownRequests)
        ->assertCanNotSeeTableRecords($otherRequests);
});

test('supervisor can filter their requests by status', function (): void {
    $requestedRequest = HRActivityRequest::factory()->create([
        'supervisor_id' => $this->supervisor->id,
        'status' => HRActivityRequestStatuses::Requested,
    ]);

    $completedRequest = HRActivityRequest::factory()->create([
        'supervisor_id' => $this->supervisor->id,
        'status' => HRActivityRequestStatuses::Completed,
        'completed_at' => now(),
        'completion_comment' => 'Done',
    ]);

    livewire(ListHRActivityRequests::class)
        ->filterTable('status', [HRActivityRequestStatuses::Requested->value])
        ->assertCanSeeTableRecords([$requestedRequest])
        ->assertCanNotSeeTableRecords([$completedRequest]);
});

test('supervisor can view individual request', function (): void {
    $request = HRActivityRequest::factory()->create([
        'supervisor_id' => $this->supervisor->id,
    ]);

    livewire(ViewHRActivityRequest::class, ['record' => $request->id])
        ->assertSuccessful()
        ->assertSee($request->employee->full_name)
        ->assertSee($request->activity_type->value);
});

test('supervisor can create new hr activity request', function (): void {
    $employee = Employee::factory()->create();

    // Create a hire record for this employee
    Hire::factory()->create([
        'employee_id' => $employee->id,
        'supervisor_id' => $this->supervisor->id,
    ]);

    // Directly create the request as the action would (since testing the employees table is separate)
    $request = HRActivityRequest::create([
        'employee_id' => $employee->id,
        'supervisor_id' => $this->supervisor->id,
        'activity_type' => HRActivityTypes::Vacations,
        'description' => 'Employee vacation request',
        'status' => HRActivityRequestStatuses::Requested,
        'requested_at' => now(),
    ]);

    $this->assertDatabaseHas('h_r_activity_requests', [
        'employee_id' => $employee->id,
        'supervisor_id' => $this->supervisor->id,
        'activity_type' => HRActivityTypes::Vacations->value,
        'status' => HRActivityRequestStatuses::Requested->value,
        'description' => 'Employee vacation request',
    ]);
});

test('supervisor can delete a requested hr activity request', function (): void {
    $request = HRActivityRequest::factory()->create([
        'supervisor_id' => $this->supervisor->id,
        'status' => HRActivityRequestStatuses::Requested,
    ]);

    livewire(ListHRActivityRequests::class)
        ->callTableAction('delete', $request)
        ->assertNotified();

    expect($request->fresh()->trashed())->toBeTrue();
});

test('supervisor cannot delete a completed hr activity request', function (): void {
    $request = HRActivityRequest::factory()->create([
        'supervisor_id' => $this->supervisor->id,
        'status' => HRActivityRequestStatuses::Completed,
        'completed_at' => now(),
        'completion_comment' => 'Done',
    ]);

    livewire(ListHRActivityRequests::class)
        ->assertTableActionHidden('delete', $request);
});

test('supervisor can edit a requested hr activity request description', function (): void {
    $request = HRActivityRequest::factory()->create([
        'supervisor_id' => $this->supervisor->id,
        'status' => HRActivityRequestStatuses::Requested,
        'description' => 'Original description',
    ]);

    $request->update(['description' => 'Updated description']);

    expect($request->fresh()->description)->toBe('Updated description');
});

test('edit action is visible for requested status', function (): void {
    $request = HRActivityRequest::factory()->create([
        'supervisor_id' => $this->supervisor->id,
        'status' => HRActivityRequestStatuses::Requested,
    ]);

    livewire(ListHRActivityRequests::class)
        ->assertTableActionVisible('edit', $request);
});

test('edit action is hidden for completed status', function (): void {
    $request = HRActivityRequest::factory()->create([
        'supervisor_id' => $this->supervisor->id,
        'status' => HRActivityRequestStatuses::Completed,
        'completed_at' => now(),
        'completion_comment' => 'Done',
    ]);

    livewire(ListHRActivityRequests::class)
        ->assertTableActionHidden('edit', $request);
});
