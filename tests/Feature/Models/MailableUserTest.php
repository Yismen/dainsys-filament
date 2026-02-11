<?php

use App\Models\Mailable;
use App\Models\MailableUser;
use App\Models\User;

test('mailing subscriptions model interacts with db table', function (): void {
    $user = User::factory()->create();
    $mailable = Mailable::factory()->create();

    $user->mailables()->attach($mailable);

    $this->assertDatabaseHas(MailableUser::class, [
        'user_id' => $user->id,
        'mailable_id' => $mailable->id,
    ]);
});

test('mailing subscriptions model relations', function (): void {
    $user = User::factory()->create();
    $mailable = Mailable::factory()->create();

    $user->mailables()->attach($mailable);

    $mailableUser = MailableUser::first();

    expect($mailableUser->user)->toBeInstanceOf(User::class);
    expect($mailableUser->mailable)->toBeInstanceOf(Mailable::class);
});
