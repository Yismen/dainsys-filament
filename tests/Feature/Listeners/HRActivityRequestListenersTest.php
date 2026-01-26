<?php

use App\Events\HRActivityRequestCompleted;
use App\Events\HRActivityRequestCreated;
use App\Listeners\SendHRActivityRequestCompletedNotification;
use App\Listeners\SendHRActivityRequestCreatedNotification;
use App\Mail\HRActivityRequestCompletedMail;
use App\Mail\HRActivityRequestCreatedMail;
use App\Models\HRActivityRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Mail::fake();

    // Create roles
    Role::firstOrCreate(['name' => 'Human Resource Manager']);
    Role::firstOrCreate(['name' => 'Human Resource Agent']);
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

    // Should send to supervisor
    Mail::assertSent(HRActivityRequestCreatedMail::class, function ($mail) use ($request) {
        return $mail->hasTo($request->supervisor->user->email);
    });

    // Should send to HR Manager
    Mail::assertSent(HRActivityRequestCreatedMail::class, function ($mail) use ($hrManager) {
        return $mail->hasTo($hrManager->email);
    });

    // Should send to HR Agent
    Mail::assertSent(HRActivityRequestCreatedMail::class, function ($mail) use ($hrAgent) {
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

    // Should send to supervisor
    Mail::assertSent(HRActivityRequestCompletedMail::class, function ($mail) use ($request) {
        return $mail->hasTo($request->supervisor->user->email);
    });

    // Should send to HR Manager
    Mail::assertSent(HRActivityRequestCompletedMail::class, function ($mail) use ($hrManager) {
        return $mail->hasTo($hrManager->email);
    });

    // Should send to HR Agent
    Mail::assertSent(HRActivityRequestCompletedMail::class, function ($mail) use ($hrAgent) {
        return $mail->hasTo($hrAgent->email);
    });
});
