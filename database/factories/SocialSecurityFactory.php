<?php

namespace Database\Factories;

use App\Models\Afp;
use App\Models\Ars;
use App\Models\Employee;
use App\Models\SocialSecurity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SocialSecurity>
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
            'employee_id' => Employee::factory(),
            'ars_id' => Ars::factory(),
            'afp_id' => Afp::factory(),
            'number' => $this->faker->unique()->numerify('SS-########'),
        ];
    }
}
