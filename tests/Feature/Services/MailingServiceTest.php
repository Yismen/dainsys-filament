<?php

use App\Models\User;
use App\Models\Mailable;
use App\Console\Commands\SyncMailables;

it('retunrs mailing files as array', function () {
    $files = \App\Services\MailingService::toArray();

    expect($files)->toBeArray();
});

it('retunrs mailing users as collection', function () {
    $this->artisan(SyncMailables::class);
    $user = User::factory()->create();

    $mailable = Mailable::first();

    $user->mailables()->attach($mailable);

    $users = \App\Services\MailingService::subscribers($mailable->name);

    expect($users->first())->toBeInstanceOf(User::class);
    expect($users)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});
