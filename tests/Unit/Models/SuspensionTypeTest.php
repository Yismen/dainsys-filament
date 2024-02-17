<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\SuspensionType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuspensionTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function suspension_types_model_interacts_with_db_table()
    {
        $data = SuspensionType::factory()->make();

        SuspensionType::create($data->toArray());

        $this->assertDatabaseHas('suspension_types', $data->only([
            'name', 'description'
        ]));
    }
}
