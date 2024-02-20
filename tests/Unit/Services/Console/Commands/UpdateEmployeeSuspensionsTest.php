<?php

namespace Tests\Unit\Services\Console\Commands;

use Tests\TestCase;
use App\Models\Employee;
use App\Models\Suspension;
use App\Enums\EmployeeStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Console\Commands\UpdateEmployeeSuspensions;

class UpdateEmployeeSuspensionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function install_command_creates_site()
    {
        $this->artisan('dainsys:update-employee-suspensions')
            ->assertSuccessful();
    }

    /** @test */
    public function current_employees_are_suspended()
    {
        $current = Employee::factory()->createQuietly();
        Suspension::factory()->createQuietly([
            'employee_id' => $current->id,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);
        $current->update(['status' => EmployeeStatus::CURRENT]);

        $this->artisan(UpdateEmployeeSuspensions::class);

        $this->assertDatabaseHas('employees', [
            'id' => $current->id,
            'status' => EmployeeStatus::SUSPENDED,
        ]);
    }

    /** @test */
    public function inactive_employees_are_not_suspended()
    {
        $current = Employee::factory()->inactive()->createQuietly();
        Suspension::factory()->createQuietly([
            'employee_id' => $current->id,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);
        $current->update(['status' => EmployeeStatus::INACTIVE]);

        $this->artisan(UpdateEmployeeSuspensions::class);

        $this->assertDatabaseHas('employees', [
            'id' => $current->id,
            'status' => EmployeeStatus::INACTIVE,
        ]);
    }

    /** @test */
    public function inactive_employees_should_not_be_suspended()
    {
        $inactive = Employee::factory()->inactive()->createQuietly();

        Suspension::factory()->createQuietly([
            'employee_id' => $inactive->id,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);

        $this->artisan(UpdateEmployeeSuspensions::class);

        $this->assertDatabaseMissing('employees', [
            'id' => $inactive->id,
            'status' => EmployeeStatus::SUSPENDED,
        ]);
    }

    /** @test */
    public function employee_is_not_suspended_if_starts_at_is_before_now()
    {
        $current = Employee::factory()->createQuietly();
        Suspension::factory()->createQuietly([
            'employee_id' => $current->id,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay(),
        ]);
        $current->update(['status' => EmployeeStatus::CURRENT]);

        $this->artisan(UpdateEmployeeSuspensions::class);

        $this->assertDatabaseHas('employees', [
            'id' => $current->id,
            'status' => EmployeeStatus::CURRENT,
        ]);
    }

    /** @test */
    public function employee_is_not_suspended_if_ends_at_is_after_now()
    {
        $current = Employee::factory()->createQuietly();
        Suspension::factory()->createQuietly([
            'employee_id' => $current->id,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->subDay(),
        ]);
        $current->update(['status' => EmployeeStatus::CURRENT]);

        $this->artisan(UpdateEmployeeSuspensions::class);

        $this->assertDatabaseHas('employees', [
            'id' => $current->id,
            'status' => EmployeeStatus::CURRENT,
        ]);
    }

    /** @test */
    public function suspended_employees_are_activated_if_today_is_prior_to_starts_at()
    {
        $current = Employee::factory()->suspended()->createQuietly();
        Suspension::factory()->createQuietly([
            'employee_id' => $current->id,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay(),
        ]);
        $current->update(['status' => EmployeeStatus::SUSPENDED]);

        $this->artisan(UpdateEmployeeSuspensions::class);

        $this->assertDatabaseHas('employees', [
            'id' => $current->id,
            'status' => EmployeeStatus::CURRENT,
        ]);
    }

    /** @test */
    public function suspended_employees_are_activated_if_today_is_after_ends_at()
    {
        $current = Employee::factory()->suspended()->createQuietly();
        Suspension::factory()->createQuietly([
            'employee_id' => $current->id,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->subDay(),
        ]);
        $current->update(['status' => EmployeeStatus::SUSPENDED]);

        $this->artisan(UpdateEmployeeSuspensions::class);

        $this->assertDatabaseHas('employees', [
            'id' => $current->id,
            'status' => EmployeeStatus::CURRENT,
        ]);
    }
}
