<?php

namespace Tests\Unit\Models;

use App\Models\Ars;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function arss_model_interacts_with_db_table()
    {
        $data = Ars::factory()->make();

        Ars::create($data->toArray());

        $this->assertDatabaseHas(Ars::class, $data->only([
            'name', 'description'
        ]));
    }

    /** @test */
    public function ars_model_uses_soft_delete()
    {
        $ars = Ars::factory()->create();

        $ars->delete();

        $this->assertSoftDeleted(Ars::class, $ars->toArray());
    }

    /** @test */
    public function arss_model_morph_one_information()
    {
        $ars = Ars::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class, $ars->information());
    }

    /** @test */
    public function arss_model_has_many_employees()
    {
        $ars = Ars::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $ars->employees());
    }
}
