<?php

namespace Database\Factories;

use App\Enums\StageOutcome;
use App\Models\Application;
use App\Models\ApplicationStageEvent;
use App\Models\RecruitmentStage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApplicationStageEvent>
 */
class ApplicationStageEventFactory extends Factory
{
    protected $model = ApplicationStageEvent::class;

    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'recruitment_stage_id' => RecruitmentStage::factory(),
            'outcome' => StageOutcome::Pending,
            'scheduled_at' => $this->faker->optional(0.7)->dateTimeBetween('-1 month', '+1 month'),
            'completed_at' => null,
            'notes' => $this->faker->optional(0.4)->sentence(),
        ];
    }

    public function passed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'outcome' => StageOutcome::Passed,
            'completed_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'outcome' => StageOutcome::Failed,
            'completed_at' => now(),
        ]);
    }
}
