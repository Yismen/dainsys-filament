<?php

namespace Tests\Unit\Models;

use App\Models\Afp;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AfpTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function afps_model_interacts_with_db_table()
    {
        $this->withoutExceptionHandling();
        $data = Afp::factory()->make();

        Afp::create($data->toArray());

        $this->assertDatabaseHas(Afp::class, $data->only([
            'name', 'description'
        ]));
    }

    /** @test */
    public function afp_model_uses_soft_delete()
    {
        $afp = Afp::factory()->create();

        $afp->delete();

        $this->assertSoftDeleted(Afp::class, $afp->toArray());
    }

    /** @test */
    public function afps_model_morph_one_information()
    {
        $afp = Afp::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class, $afp->information());
    }

    /** @test */
    public function afps_model_has_many_employees()
    {
        $afp = Afp::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $afp->employees());
    }
}
