<?php

namespace Database\Factories;

use App\Models\QAForm;
use App\Models\QAQuestion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QAQuestion>
 */
class QAQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'qa_form_id' => QAForm::factory(),
            'text' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'max_points' => $this->faker->numberBetween(3, 10),
            'display_order' => $this->faker->numberBetween(1, 20),
            'is_active' => true,
            'author_id' => User::factory(),
        ];
    }
}
