<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\MailingSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MailingSubscriptionFactory>
 */
class MailingSubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MailingSubscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'mailable' => $this->faker->word(),
            'user_id' => User::factory(),
        ];
    }
}
