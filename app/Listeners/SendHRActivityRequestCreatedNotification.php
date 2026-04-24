<?php

namespace App\Listeners;

use App\Events\HRActivityRequestCreated;
use App\Models\User;
use App\Notifications\HRActivity\HRActivityRequestCreatedNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class SendHRActivityRequestCreatedNotification
{
    public function handle(HRActivityRequestCreated $event): void
    {
        $request = $event->request;
        $supervisor = $request->supervisor;
        $hrUsers = User::role(['Human Resource Manager', 'Human Resource Agent'])->get();

        $recipients = Collection::make([$supervisor->user])
            ->filter()
            ->merge($hrUsers)
            ->unique('id')
            ->values();

        Notification::send($recipients, new HRActivityRequestCreatedNotification($request));
    }
}
