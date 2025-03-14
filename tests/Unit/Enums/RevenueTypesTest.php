<?php

namespace Tests\Unit\Enums;


use App\Enums\RevenueTypes;
use PHPUnit\Framework\TestCase;

class RevenueTypesTest extends TestCase
{
    /** @test */
    public function values_method_return_specific_values()
    {
        $this->assertEquals([
            'Login Time',
            'Production Time',
            'Talk Time',
            'Sales',
        ], RevenueTypes::values());
    }

    /** @test */
    public function all_method_return_associative_array()
    {
        $this->assertEquals([
            'Login Time' => 'LoginTime',
            'Production Time' => 'ProductionTime',
            'Talk Time' => 'TalkTime',
            'Sales' => 'Sales',
        ], RevenueTypes::toArray());
    }
}
