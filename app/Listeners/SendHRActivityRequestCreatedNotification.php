<?php

namespace App\Listeners;

use App\Events\HRActivityRequestCreated;
use App\Mail\HRActivityRequestCreatedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendHRActivityRequestCreatedNotification
{
    public function handle(HRActivityRequestCreated $event): void
    {
        $request = $event->request;
        $supervisor = $request->supervisor;

        // Send to supervisor who created the request
        if ($supervisor->user?->email) {
            Mail::to($supervisor->user->email)
                ->send(new HRActivityRequestCreatedMail($request));
        }

        // Send to all users with HR Manager or HR Agent roles
        $hrUsers = User::role(['Human Resource Manager', 'Human Resource Agent'])->get();

        foreach ($hrUsers as $hrUser) {
            Mail::to($hrUser->email)
                ->send(new HRActivityRequestCreatedMail($request));
        }
    }
}
