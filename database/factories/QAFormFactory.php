<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\QAForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QAForm>
 */
class QAFormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->sentence(3),
            'project_id' => Project::factory(),
            'passing_threshold_percentage' => $this->faker->numberBetween(70, 95),
            'description' => $this->faker->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
