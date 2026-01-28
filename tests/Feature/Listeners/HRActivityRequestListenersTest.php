<?php

use App\Events\HRActivityRequestCompleted;
use App\Events\HRActivityRequestCreated;
use App\Listeners\SendHRActivityRequestCompletedNotification;
use App\Listeners\SendHRActivityRequestCreatedNotification;
use App\Mail\HRActivityRequestCompletedMail;
use App\Mail\HRActivityRequestCreatedMail;
use App\Models\HRActivityRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Mail::fake();
    Event::fake(); // Prevent automatic listener execution

    // Create roles with proper UUIDs using DB::table
    DB::table('roles')->insert([
        'id' => \Illuminate\Support\Str::uuid()->toString(),
        'name' => 'Human Resource Manager',
        'guard_name' => 'web',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('roles')->insert([
        'id' => \Illuminate\Support\Str::uuid()->toString(),
        'name' => 'Human Resource Agent',
        'guard_name' => 'web',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
});

test('hr activity request created notification sends emails to supervisor and hr staff', function () {
    $request = HRActivityRequest::factory()->create();

    // Create HR users with roles
    $hrManager = User::factory()->create();
    $hrManager->assignRole('Human Resource Manager');

    $hrAgent = User::factory()->create();
    $hrAgent->assignRole('Human Resource Agent');

    $event = new HRActivityRequestCreated($request);
    $listener = new SendHRActivityRequestCreatedNotification;

    $listener->handle($event);

    // Should queue mail to supervisor
    Mail::assertQueued(HRActivityRequestCreatedMail::class, function ($mail) use ($request) {
        return $mail->hasTo($request->supervisor->user->email);
    });

    // Should queue mail to HR Manager
    Mail::assertQueued(HRActivityRequestCreatedMail::class, function ($mail) use ($hrManager) {
        return $mail->hasTo($hrManager->email);
    });

    // Should queue mail to HR Agent
    Mail::assertQueued(HRActivityRequestCreatedMail::class, function ($mail) use ($hrAgent) {
        return $mail->hasTo($hrAgent->email);
    });
});

test('hr activity request completed notification sends emails to supervisor and hr staff', function () {
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

    // Should queue mail to supervisor
    Mail::assertQueued(HRActivityRequestCompletedMail::class, function ($mail) use ($request) {
        return $mail->hasTo($request->supervisor->user->email);
    });

    // Should queue mail to HR Manager
    Mail::assertQueued(HRActivityRequestCompletedMail::class, function ($mail) use ($hrManager) {
        return $mail->hasTo($hrManager->email);
    });

    // Should queue mail to HR Agent
    Mail::assertQueued(HRActivityRequestCompletedMail::class, function ($mail) use ($hrAgent) {
        return $mail->hasTo($hrAgent->email);
    });
});
