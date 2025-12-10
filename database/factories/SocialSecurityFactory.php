<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialSecurity>
 */
class SocialSecurityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => \App\Models\Employee::factory(),
            'ars_id' => \App\Models\Ars::factory(),
            'afp_id' => \App\Models\Afp::factory(),
            'number' => $this->faker->unique()->numerify('SS-########'),
        ];
    }
}
