<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\TerminationType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TerminationTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function termination_types_model_interacts_with_db_table()
    {
        $data = TerminationType::factory()->make();

        TerminationType::create($data->toArray());

        $this->assertDatabaseHas('termination_types', $data->only([
            'name', 'description'
        ]));
    }

    /** @test */
    public function termination_type_model_uses_soft_delete()
    {
        $termination_type = TerminationType::factory()->create();

        $termination_type->delete();

        $this->assertSoftDeleted(TerminationType::class, $termination_type->toArray());
    }
}
