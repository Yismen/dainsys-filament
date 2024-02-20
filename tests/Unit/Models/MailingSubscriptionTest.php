<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\MailingSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MailingSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mailing_subscriptions_model_interacts_with_db_table()
    {
        $data = MailingSubscription::factory()->make();

        MailingSubscription::create($data->toArray());

        $this->assertDatabaseHas('mailing_subscriptions', $data->only([
            'mailable', 'user_id'
        ]));
    }

    /** @test */
    public function mailing_subscriptions_model_belongs_to_a_user()
    {
        $mailing_subscription = MailingSubscription::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $mailing_subscription->user());
    }
}
