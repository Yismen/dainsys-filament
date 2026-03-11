<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Universal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Universal>
 */
class UniversalFactory extends Factory
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
            'date_since' => now()->format('Y-m-d'),
        ];
    }
}
