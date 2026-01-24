<?php

use App\Models\MailableUser;

test('mailing subscriptions model interacts with db table', function () {
    $data = MailableUser::factory()->make();

    MailableUser::create($data->toArray());

    $this->assertDatabaseHas('mailing_subscriptions', $data->only([
        'mailable', 'user_id',
    ]));
});

test('mailing subscriptions model belongs to a user', function () {
    $mailing_subscription = MailableUser::factory()->create();

    expect($mailing_subscription->user())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
