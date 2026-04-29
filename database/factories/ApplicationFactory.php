<?php

namespace Database\Factories;

use App\Enums\ApplicationStatuses;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\JobOpening;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        return [
            'applicant_id' => Applicant::factory(),
            'job_opening_id' => JobOpening::factory(),
            'status' => ApplicationStatuses::Applied,
            'notes' => $this->faker->optional(0.5)->sentence(),
            'applied_at' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
        ];
    }

    public function hired(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ApplicationStatuses::Hired,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ApplicationStatuses::Rejected,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ApplicationStatuses::InProgress,
        ]);
    }
}
