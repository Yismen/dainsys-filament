<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function sites_model_interacts_with_db_table()
    {
        $data = Site::factory()->make();

        Site::create($data->toArray());

        $this->assertDatabaseHas('sites', $data->only([
            'name', 'description'
        ]));
    }

    /** @test */
    public function sites_model_morph_one_information()
    {
        $site = Site::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class, $site->information());
    }

    /** @test */
    public function sites_model_has_many_employees()
    {
        $site = Site::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $site->employees());
    }
}
