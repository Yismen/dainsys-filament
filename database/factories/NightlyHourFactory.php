<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\NightlyHour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NightlyHour>
 */
class NightlyHourFactory extends Factory
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
            'date' => $this->faker->date(),
            'total_hours' => $this->faker->randomFloat(2, 0, 4),
        ];
    }
}
