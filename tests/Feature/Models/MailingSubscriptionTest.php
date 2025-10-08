<?php

use App\Models\MailingSubscription;

test('mailing subscriptions model interacts with db table', function () {
    $data = MailingSubscription::factory()->make();

    MailingSubscription::create($data->toArray());

    $this->assertDatabaseHas('mailing_subscriptions', $data->only([
        'mailable', 'user_id'
    ]));
});

test('mailing subscription model uses soft delete', function () {
    $mailing_subscription = MailingSubscription::factory()->create();

    $mailing_subscription->delete();

    $this->assertSoftDeleted(MailingSubscription::class, $mailing_subscription->only(['id']));
});

test('mailing subscriptions model belongs to a user', function () {
    $mailing_subscription = MailingSubscription::factory()->create();

    expect($mailing_subscription->user())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
