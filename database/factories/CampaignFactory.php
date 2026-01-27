<?php

namespace Database\Factories;

use App\Enums\RevenueTypes;
use App\Models\Project;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'project_id' => Project::factory(),
            'source_id' => Source::factory(),
            'revenue_type' => RevenueTypes::LoginTime,
            'sph_goal' => rand(3, 40),
            'revenue_rate' => rand(10, 30),
            'description' => $this->faker->sentence(),
        ];
    }

    public function downtime(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'revenue_type' => RevenueTypes::Downtime,
            ];
        });
    }
}
