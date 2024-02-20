<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_model_interacts_with_db_table()
    {
        $data = User::factory()->create();

        $this->assertDatabaseHas('users', $data->only([
            'name', 'email', 'email_verified_at', 'remember_token'
        ]));
    }
    /** @test */
    public function users_model_has_many_mailing_subscriptions()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->mailingSubscriptions());
    }
}
