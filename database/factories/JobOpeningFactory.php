<?php

namespace Database\Factories;

use App\Enums\JobOpeningStatuses;
use App\Models\JobOpening;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobOpening>
 */
class JobOpeningFactory extends Factory
{
    protected $model = JobOpening::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->optional(0.7)->paragraph(),
            'status' => JobOpeningStatuses::Open,
            'position_id' => null,
            'department_id' => null,
            'site_id' => null,
            'openings_count' => $this->faker->numberBetween(1, 5),
            'opened_at' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'closed_at' => null,
        ];
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => JobOpeningStatuses::Closed,
            'closed_at' => now()->format('Y-m-d'),
        ]);
    }

    public function onHold(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => JobOpeningStatuses::OnHold,
        ]);
    }
}
