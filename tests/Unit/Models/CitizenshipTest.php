<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Citizenship;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CitizenshipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function citizenships_model_interacts_with_db_table()
    {
        $data = Citizenship::factory()->make();

        Citizenship::create($data->toArray());

        $this->assertDatabaseHas('citizenships', $data->only([
            'name', 'description'
        ]));
    }

    /** @test */
    public function citizenship_model_uses_soft_delete()
    {
        $citizenship = Citizenship::factory()->create();

        $citizenship->delete();

        $this->assertSoftDeleted(Citizenship::class, $citizenship->toArray());
    }

    /** @test */
    public function citizenships_model_has_many_employees()
    {
        $citizenship = Citizenship::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $citizenship->employees());
    }
}
