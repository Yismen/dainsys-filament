<?php

use App\Console\Commands\SyncMailables;
use App\Models\Mailable;
use App\Models\User;
use App\Services\MailingService;
use Illuminate\Support\Collection;

it('retunrs mailing files as array', function (): void {
    $files = MailingService::toArray();

    expect($files)->toBeArray();
});

it('retunrs mailing users as collection', function (): void {
    $this->artisan(SyncMailables::class);

    $mailable = Mailable::first();
    $user = User::factory()->create();
    $user->mailables()->attach($mailable);

    $users = MailingService::subscribers($mailable->name);

    expect($users->first())->toBeInstanceOf(User::class);
    expect($users)->toBeInstanceOf(Collection::class);
});

it('returns a null user instance if no users are subscribed', function (): void {
    $this->artisan(SyncMailables::class);
    $mailable = Mailable::first();

    $users = MailingService::subscribers($mailable->name);
    expect($users)->toBeInstanceOf(Collection::class);
});
