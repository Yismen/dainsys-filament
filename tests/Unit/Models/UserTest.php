<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\MailingSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_model_interacts_with_db_table()
    {
        $data = User::factory()->create();

        $this->assertDatabaseHas('users', $data->only([
            'name',
            'email',
            'email_verified_at',
            'remember_token'
        ]));
    }



    /** @test */
    public function user_model_uses_soft_delete()
    {
        $user = User::factory()->create();

        $user->delete();

        $this->assertSoftDeleted(User::class, $user->toArray());
    }

    /** @test */
    public function users_model_has_many_mailing_subscriptions()
    {
        $user = User::factory()->create();

        MailingSubscription::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->mailingSubscriptions());
        $this->assertInstanceOf(MailingSubscription::class, $user->mailingSubscriptions->first());
    }
}
