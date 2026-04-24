<?php

use App\Events\HRActivityRequestCompleted;
use App\Events\HRActivityRequestCreated;
use App\Listeners\SendHRActivityRequestCompletedNotification;
use App\Listeners\SendHRActivityRequestCreatedNotification;
use App\Models\HRActivityRequest;
use App\Models\User;
use App\Notifications\HRActivity\HRActivityRequestCompletedNotification;
use App\Notifications\HRActivity\HRActivityRequestCreatedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

beforeEach(function (): void {
    Notification::fake();
    Event::fake(); // Prevent automatic listener execution

    // Create roles with proper UUIDs using DB::table
    DB::table('roles')->insert([
        'id' => Str::uuid()->toString(),
        'name' => 'Human Resource Manager',
        'guard_name' => 'web',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('roles')->insert([
        'id' => Str::uuid()->toString(),
        'name' => 'Human Resource Agent',
        'guard_name' => 'web',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
});

test('hr activity request created notification sends notifications to supervisor and hr staff', function (): void {
    $request = HRActivityRequest::factory()->create();

    // Create HR users with roles
    $hrManager = User::factory()->create();
    $hrManager->assignRole('Human Resource Manager');

    $hrAgent = User::factory()->create();
    $hrAgent->assignRole('Human Resource Agent');

    $event = new HRActivityRequestCreated($request);
    $listener = new SendHRActivityRequestCreatedNotification;

    $listener->handle($event);

    Notification::assertSentTo($request->supervisor->user, HRActivityRequestCreatedNotification::class);
    Notification::assertSentTo($hrManager, HRActivityRequestCreatedNotification::class);
    Notification::assertSentTo($hrAgent, HRActivityRequestCreatedNotification::class);
});

test('hr activity request completed notification sends notifications to supervisor and hr staff', function (): void {
    $request = HRActivityRequest::factory()->create();
    $comment = 'Request has been completed';

    // Create HR users with roles
    $hrManager = User::factory()->create();
    $hrManager->assignRole('Human Resource Manager');

    $hrAgent = User::factory()->create();
    $hrAgent->assignRole('Human Resource Agent');

    $event = new HRActivityRequestCompleted($request, $comment);
    $listener = new SendHRActivityRequestCompletedNotification;

    $listener->handle($event);

    Notification::assertSentTo($request->supervisor->user, HRActivityRequestCompletedNotification::class);
    Notification::assertSentTo($hrManager, HRActivityRequestCompletedNotification::class);
    Notification::assertSentTo($hrAgent, HRActivityRequestCompletedNotification::class);
});
