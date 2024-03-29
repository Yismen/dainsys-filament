<?php

namespace Tests\Unit\Enums;


use App\Enums\MaritalStatus;
use PHPUnit\Framework\TestCase;

class MaritalStatusTest extends TestCase
{
    /** @test */
    public function values_method_return_specific_values()
    {
        $this->assertEquals([
            'Single',
            'Married',
            'Divorced',
            'Free Union',
        ], MaritalStatus::values());
    }

    /** @test */
    public function all_method_return_associative_array()
    {
        $this->assertEquals([
            'Single' => 'Single',
            'Married' => 'Married',
            'Divorced' => 'Divorced',
            'FreeUnion' => 'Free Union',
        ], MaritalStatus::toArray());
    }
}
