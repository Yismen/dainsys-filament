<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Hire;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hire>
 */
class HireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => now(),
            'employee_id' => Employee::factory(),
            'site_id' => Site::factory(),
            'project_id' => Project::factory(),
            'position_id' => Position::factory(),
            'supervisor_id' => Supervisor::factory(),
        ];
    }
}
