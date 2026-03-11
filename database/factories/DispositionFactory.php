<?php

namespace Database\Factories;

use App\Models\Disposition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Disposition>
 */
class DispositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'sales' => $this->faker->randomFloat(2, 1000, 10000),
            'description' => $this->faker->sentence(),
        ];
    }
}
