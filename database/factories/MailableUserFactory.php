<?php

namespace Database\Factories;

use App\Models\Mailable;
use App\Models\MailingSubscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MailingSubscription>
 */
class MailableUserFactory extends Factory
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
            'mailable_id' => Mailable::factory(),
            'user_id' => User::factory(),
        ];
    }
}
