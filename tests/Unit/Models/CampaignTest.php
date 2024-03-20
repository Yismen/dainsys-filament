<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function campaigns_model_interacts_with_db_table()
    {
        $data = Campaign::factory()->make();

        Campaign::create($data->toArray());

        $this->assertDatabaseHas('campaigns', $data->only([
            'name', 'project_id', 'source', 'revenue_type', 'goal', 'rate'
        ]));
    }

    /** @test */
    public function campaign_model_uses_soft_delete()
    {
        $campaign = Campaign::factory()->create();

        $campaign->delete();

        $this->assertSoftDeleted(Campaign::class, $campaign->toArray());
    }

    /** @test */
    public function campaigns_model_belongs_to_project()
    {
        $campaign = Campaign::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $campaign->project());
    }
}
