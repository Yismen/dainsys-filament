<?php

namespace Database\Factories;

use App\Models\RecruitmentStage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecruitmentStage>
 */
class RecruitmentStageFactory extends Factory
{
    protected $model = RecruitmentStage::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->optional(0.7)->sentence(),
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
