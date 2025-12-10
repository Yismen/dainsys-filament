<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hire>
 */
class HireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'employee_id' => \App\Models\Employee::factory(),
            'site_id' => \App\Models\Site::factory(),
            'project_id' => \App\Models\Project::factory(),
            'position_id' => \App\Models\Position::factory(),
            'supervisor_id' => \App\Models\Supervisor::factory(),
            'punch' => $this->faker->word(),
        ];
    }
}
