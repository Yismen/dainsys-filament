<?php

namespace Tests\Unit\Enums;


use App\Enums\CampaignSources;
use PHPUnit\Framework\TestCase;

class CampaignSourcesTest extends TestCase
{
    /** @test */
    public function values_method_return_specific_values()
    {
        $this->assertEquals([
            'Chat',
            'Email',
            'Inbound',
            'Outbound',
            'QAReview',
            'Resubmissions',
            'Training',
        ], CampaignSources::values());
    }

    /** @test */
    public function all_method_return_associative_array()
    {
        $this->assertEquals([
            'Chat' => 'Chat',
            'Email' => 'Email',
            'Inbound' => 'Inbound',
            'Outbound' => 'Outbound',
            'QAReview' => 'QAReview',
            'Resubmissions' => 'Resubmissions',
            'Training' => 'Training',
        ], CampaignSources::toArray());
    }
}
