<?php

use App\Models\MailingSubscription;
use App\Models\User;

test('users model interacts with db table', function () {
    $data = User::factory()->create();

    $this->assertDatabaseHas('users', $data->only([
        'name',
        'email',
        'email_verified_at',
        'remember_token',
    ]));
});

test('users model has many mailing subscriptions', function () {
    $user = User::factory()->create();

    MailingSubscription::factory()->create(['user_id' => $user->id]);

    expect($user->mailingSubscriptions())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($user->mailingSubscriptions->first())->toBeInstanceOf(MailingSubscription::class);
});
