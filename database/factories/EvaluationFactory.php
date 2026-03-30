<?php

namespace Database\Factories;

use App\Enums\EvaluationStatuses;
use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\QAForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Evaluation>
 */
class EvaluationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'record_number' => 'EVAL-'.strtoupper(Str::random(8)),
            'evaluation_date' => now()->toDateString(),
            'employee_id' => Employee::factory()->hired(),
            'supervisor_id' => null,
            'evaluator_id' => User::factory(),
            'qa_form_id' => QAForm::factory(),
            'threshold_percentage' => $this->faker->numberBetween(70, 95),
            'points_possible' => 0,
            'points_achieved' => 0,
            'success_percentage' => 0,
            'status' => EvaluationStatuses::Draft->value,
            'comments' => $this->faker->sentence(),
        ];
    }
}
