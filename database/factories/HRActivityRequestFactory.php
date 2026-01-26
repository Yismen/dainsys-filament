<?php

namespace Database\Factories;

use App\Enums\HRActivityRequestStatuses;
use App\Enums\HRActivityTypes;
use App\Models\Employee;
use App\Models\HRActivityRequest;
use App\Models\Supervisor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HRActivityRequest>
 */
class HRActivityRequestFactory extends Factory
{
    protected $model = HRActivityRequest::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'supervisor_id' => Supervisor::factory(),
            'activity_type' => $this->faker->randomElement(HRActivityTypes::cases()),
            'status' => HRActivityRequestStatuses::Requested,
            'description' => $this->faker->sentence(),
            'requested_at' => now(),
        ];
    }
}
