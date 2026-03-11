<?php

namespace Database\Factories;

use App\Enums\AbsenceStatuses;
use App\Enums\AbsenceTypes;
use App\Models\Absence;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsenceFactory extends Factory
{
    protected $model = Absence::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'date' => $this->faker->date(),
            'status' => AbsenceStatuses::Created,
            'type' => null,
            'comment' => $this->faker->optional(0.7)->sentence(),
            'created_by' => User::factory(),
        ];
    }

    public function reported(AbsenceTypes $type = AbsenceTypes::Unjustified): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => AbsenceStatuses::Reported,
            'type' => $type,
        ]);
    }

    public function justified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => AbsenceTypes::Justified,
        ]);
    }

    public function unjustified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => AbsenceTypes::Unjustified,
        ]);
    }
}
