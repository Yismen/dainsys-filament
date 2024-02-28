<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Supervisor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupervisorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function supervisors_model_interacts_with_db_table()
    {
        $data = Supervisor::factory()->make();

        Supervisor::create($data->toArray());

        $this->assertDatabaseHas('supervisors', $data->only([
            'name', 'description'
        ]));
    }

    /** @test */
    public function supervisors_model_has_many_employees()
    {
        $supervisor = Supervisor::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $supervisor->employees());
    }
}
