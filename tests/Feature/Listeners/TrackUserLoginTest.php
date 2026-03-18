<?php

use App\Listeners\TrackUserLogin;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

test('login event is wired to track user login listener', function (): void {
    Event::fake([Login::class]);

    Event::assertListening(Login::class, TrackUserLogin::class);
});

test('listener stores user login metadata in activity log', function (): void {
    $user = User::factory()->create([
        'name' => 'Agent User',
        'email' => 'agent@example.com',
    ]);

    $request = Request::create('/login', 'POST', server: [
        'REMOTE_ADDR' => '203.0.113.8',
        'HTTP_USER_AGENT' => 'Pest Browser/'.Str::uuid(),
    ]);

    $userAgent = $request->userAgent();

    app()->instance('request', $request);

    event(new Login('web', $user, false));

    /** @var Activity|null $activity */
    $activity = Activity::query()->latest('id')->first();

    expect($activity)->not->toBeNull();
    expect($activity->log_name)->toBe('authentication');
    expect($activity->description)->toBe('User logged in');
    expect($activity->event)->toBe('login');
    expect($activity->causer_id)->toBe($user->id);
    expect($activity->getExtraProperty('attributes.user_id'))->toBe($user->id);
    expect($activity->getExtraProperty('attributes.name'))->toBe('Agent User');
    expect($activity->getExtraProperty('attributes.email'))->toBe('agent@example.com');
    expect($activity->getExtraProperty('attributes.ip_address'))->toBe('203.0.113.8');
    expect($activity->getExtraProperty('attributes.user_agent'))->toBe($userAgent);
    expect($activity->getExtraProperty('attributes.browser'))->toBe($userAgent);
    expect($activity->getExtraProperty('attributes.guard'))->toBe('web');
    expect($activity->getExtraProperty('attributes.remember'))->toBeFalse();

    $matchingRowsCount = Activity::query()
        ->where('description', 'User logged in')
        ->where('properties->attributes->user_agent', $userAgent)
        ->count();

    expect($matchingRowsCount)->toBe(1);
});
