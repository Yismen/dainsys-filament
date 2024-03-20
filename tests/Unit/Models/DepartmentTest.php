<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function departments_model_interacts_with_db_table()
    {
        $data = Department::factory()->make();

        Department::create($data->toArray());

        $this->assertDatabaseHas('departments', $data->only([
            'name', 'description'
        ]));
    }

    /** @test */
    public function department_model_uses_soft_delete()
    {
        $department = Department::factory()->create();

        $department->delete();

        $this->assertSoftDeleted(Department::class, $department->only(['id']));
    }

    /** @test */
    public function departments_model_has_many_positions()
    {
        $department = Department::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $department->positions());
    }

    /** @test */
    public function departments_model_has_many_employees()
    {
        $department = Department::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class, $department->employees());
    }
}
