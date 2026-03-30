<?php

use App\Console\Commands\SyncMailables;
use App\Models\Mailable;
use App\Models\User;
use App\Services\MailingService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

it('returns mailing files as array', function (): void {
    $files = MailingService::toArray();

    expect($files)->toBeArray();
});

it('returns mailing users as collection', function (): void {
    $this->artisan(SyncMailables::class);

    $mailable = Mailable::first();
    $user = User::factory()->create();
    $user->mailables()->attach($mailable);

    Cache::forget('mailing_subscriptions_for_mailable_'.$mailable->name);

    $users = MailingService::subscribers($mailable->name);

    expect($users->first())->toBeInstanceOf(User::class);
    expect($users)->toBeInstanceOf(Collection::class);
});

it('returns a null user instance if no users are subscribed', function (): void {
    $this->artisan(SyncMailables::class);
    $mailable = Mailable::first();

    Cache::forget('mailing_subscriptions_for_mailable_'.$mailable->name);

    $users = MailingService::subscribers($mailable->name);
    expect($users)->toBeInstanceOf(Collection::class);
});

it('preserves uuid ids through subscriber cache round-trips', function (): void {
    $this->artisan(SyncMailables::class);

    $mailable = Mailable::first();
    $user = User::factory()->create();
    $user->mailables()->attach($mailable);

    $cacheKey = 'mailing_subscriptions_for_mailable_'.$mailable->name;
    Cache::forget($cacheKey);

    $firstUsers = MailingService::subscribers($mailable->name, false);
    $cachedPayload = Cache::get($cacheKey);
    $secondUsers = MailingService::subscribers($mailable->name, false);

    expect($cachedPayload)->toBeArray()
        ->and($cachedPayload)->not->toBeEmpty()
        ->and($cachedPayload[0])->toHaveKeys(['id', 'name'])
        ->and($cachedPayload[0]['id'])->toBeString()
        ->and($cachedPayload[0]['id'])->toBe($user->id)
        ->and($firstUsers->pluck('id')->all())->toContain($user->id)
        ->and($secondUsers->pluck('id')->all())->toContain($user->id);
});
