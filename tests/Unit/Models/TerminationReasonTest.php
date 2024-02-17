<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\TerminationReason;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TerminationReasonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function termination_reasons_model_interacts_with_db_table()
    {
        $data = TerminationReason::factory()->make();

        TerminationReason::create($data->toArray());

        $this->assertDatabaseHas('termination_reasons', $data->only([
            'name', 'description'
        ]));
    }
}
