<?php

namespace Tests\Unit\Services\HeadCount;

use Tests\TestCase;
use App\Services\HC\ByDepartment;
use Illuminate\Support\Facades\Mail;
use Database\Factories\EmployeeFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ByDepartmentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        Mail::fake();
        EmployeeFactory::new()->create();

        $service = new ByDepartment();
        
        $this->assertArrayHasKey('name', $service->count()->toArray()[0]);
        $this->assertArrayHasKey('employees_count', $service->count()->toArray()[0]);
    }
}
