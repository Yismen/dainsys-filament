<?php

namespace Database\Factories;

use App\Models\Mailable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mailable>
 */
class MailableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'description' => $this->faker->sentence(),
        ];
    }
}
