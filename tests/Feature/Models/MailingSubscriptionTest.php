<?php

use App\Models\MailingSubscription;

test('mailing subscriptions model interacts with db table', function () {
    $data = MailingSubscription::factory()->make();

    MailingSubscription::create($data->toArray());

    $this->assertDatabaseHas('mailing_subscriptions', $data->only([
        'mailable', 'user_id'
    ]));
});

test('mailing subscriptions model belongs to a user', function () {
    $mailing_subscription = MailingSubscription::factory()->create();

    expect($mailing_subscription->user())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
