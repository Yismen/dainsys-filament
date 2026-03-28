<?php

namespace Database\Factories;

use App\Enums\EvaluationStatuses;
use App\Models\Evaluation;
use App\Models\EvaluationStatusHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EvaluationStatusHistory>
 */
class EvaluationStatusHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'evaluation_id' => Evaluation::factory(),
            'from_status' => EvaluationStatuses::Draft->value,
            'to_status' => EvaluationStatuses::Published->value,
            'changed_by' => User::factory(),
            'change_comment' => $this->faker->sentence(),
            'metadata' => [
                'source' => 'factory',
            ],
        ];
    }
}
