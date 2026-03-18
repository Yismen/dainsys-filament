<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class TrackUserLogin
{
    public function handle(Login $event): void
    {
        /** @var Authenticatable $user */
        $user = $event->user;

        $request = request();

        $ipAddress = $request->ip();
        $userAgent = $request->userAgent() ?? '';

        $logger = activity('authentication')
            ->event('login')
            ->withProperties([
                'attributes' => [
                    'user_id' => $user->getAuthIdentifier(),
                    'name' => data_get($user, 'name'),
                    'email' => data_get($user, 'email'),
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'browser' => $userAgent,
                    'guard' => $event->guard,
                    'remember' => $event->remember,
                    'logged_in_at' => now()->toDateTimeString(),
                ],
            ]);

        if ($user instanceof Model) {
            $logger->causedBy($user);
        }

        $logger->log('User logged in');
    }
}
