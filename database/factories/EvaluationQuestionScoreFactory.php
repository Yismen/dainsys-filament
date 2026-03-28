<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\EvaluationQuestionScore;
use App\Models\QAQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EvaluationQuestionScore>
 */
class EvaluationQuestionScoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $maxPoints = $this->faker->numberBetween(3, 10);

        return [
            'evaluation_id' => Evaluation::factory(),
            'qa_question_id' => QAQuestion::factory(),
            'points_awarded' => $this->faker->numberBetween(0, $maxPoints),
            'max_points_snapshot' => $maxPoints,
            'evaluator_note' => $this->faker->sentence(),
        ];
    }
}
