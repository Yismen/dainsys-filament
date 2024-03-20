<?php

namespace Database\Factories;

use App\Models\Project;
use App\Enums\RevenueTypes;
use App\Enums\CampaignSources;
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
            'source' => CampaignSources::Inbound->value,
            'revenue_type' => RevenueTypes::Sales->value,
            'goal' => rand(3, 40),
            'rate' => rand(10, 30),
        ];
    }
}
