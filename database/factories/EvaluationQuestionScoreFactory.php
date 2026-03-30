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
        return [
            'evaluation_id' => Evaluation::factory(),
            'qa_question_id' => QAQuestion::factory(),
            'points_awarded' => $this->faker->randomElement([0, 20, 40, 60, 80, 100]),
            'max_points_snapshot' => $this->faker->numberBetween(3, 10),
            'evaluator_note' => $this->faker->sentence(),
        ];
    }
}
