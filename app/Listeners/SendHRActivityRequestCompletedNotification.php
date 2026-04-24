<?php

namespace App\Listeners;

use App\Events\HRActivityRequestCompleted;
use App\Models\User;
use App\Notifications\HRActivity\HRActivityRequestCompletedNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class SendHRActivityRequestCompletedNotification
{
    public function handle(HRActivityRequestCompleted $event): void
    {
        $request = $event->request;
        $comment = $event->comment;
        $supervisor = $request->supervisor;
        $hrUsers = User::role(['Human Resource Manager', 'Human Resource Agent'])->get();

        $recipients = Collection::make([$supervisor->user])
            ->filter()
            ->merge($hrUsers)
            ->unique('id')
            ->values();

        Notification::send($recipients, new HRActivityRequestCompletedNotification($request, $comment));
    }
}
