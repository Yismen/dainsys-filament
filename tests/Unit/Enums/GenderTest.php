<?php

namespace Tests\Unit\Enums;


use App\Enums\Gender;
use PHPUnit\Framework\TestCase;

class GenderTest extends TestCase
{
    /** @test */
    public function values_method_return_specific_values()
    {
        $this->assertEquals([
            'Male',
            'Female',
            // 'Undefined',
        ], Gender::values());
    }

    /** @test */
    public function all_method_return_associative_array()
    {
        $this->assertEquals([
            'Male' => 'Male',
            'Female' => 'Female',
            // 'Undefined' => 'Undefined',
        ], Gender::toArray());
    }
}
