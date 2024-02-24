<?php

namespace Tests\Unit\Enums;


use App\Enums\EmployeeStatus;
use PHPUnit\Framework\TestCase;

class EmployeeStatusTest extends TestCase
{
    /** @test */
    public function values_method_return_specific_values()
    {
        $this->assertEquals([
            'Current',
            'Inactive',
            'Suspended',
        ], EmployeeStatus::values());
    }

    /** @test */
    public function all_method_return_associative_array()
    {
        $this->assertEquals([
            'Current' => 'Current',
            'Inactive' => 'Inactive',
            'Suspended' => 'Suspended',
        ], EmployeeStatus::toArray());
    }
}
