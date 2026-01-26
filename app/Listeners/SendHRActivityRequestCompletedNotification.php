<?php

namespace App\Listeners;

use App\Events\HRActivityRequestCompleted;
use App\Mail\HRActivityRequestCompletedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendHRActivityRequestCompletedNotification
{
    public function handle(HRActivityRequestCompleted $event): void
    {
        $request = $event->request;
        $comment = $event->comment;
        $supervisor = $request->supervisor;

        // Send to supervisor who requested the activity
        if ($supervisor->user?->email) {
            Mail::to($supervisor->user->email)
                ->send(new HRActivityRequestCompletedMail($request, $comment));
        }

        // Send to all users with HR Manager or HR Agent roles
        $hrUsers = User::role(['Human Resource Manager', 'Human Resource Agent'])->get();

        foreach ($hrUsers as $hrUser) {
            Mail::to($hrUser->email)
                ->send(new HRActivityRequestCompletedMail($request, $comment));
        }
    }
}
