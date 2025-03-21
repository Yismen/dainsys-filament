<?php

namespace Tests\Feature\Console\Commands;

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
    public function command_is_schedulled_for_daily_at_300_am()
    {
        $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
            ->filter(function ($element) {
                return str($element->command)->contains('dainsys:update-employee-suspensions');
            })->first();

        $this->assertNotNull($addedToScheduler);
        $this->assertEquals('0 3 * * *', $addedToScheduler->expression);
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
        $current->update(['status' => EmployeeStatus::Current]);

        $this->artisan(UpdateEmployeeSuspensions::class);

        $this->assertDatabaseHas('employees', [
            'id' => $current->id,
            'status' => EmployeeStatus::Suspended,
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
        $current->update(['status' => EmployeeStatus::Inactive]);

        $this->artisan(UpdateEmployeeSuspensions::class);

        $this->assertDatabaseHas('employees', [
            'id' => $current->id,
            'status' => EmployeeStatus::Inactive,
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
            'status' => EmployeeStatus::Suspended,
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
        $current->update(['status' => EmployeeStatus::Current]);

        $this->artisan(UpdateEmployeeSuspensions::class);

        $this->assertDatabaseHas('employees', [
            'id' => $current->id,
            'status' => EmployeeStatus::Current,
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
        $current->update(['status' => EmployeeStatus::Current]);

        $this->artisan(UpdateEmployeeSuspensions::class);

        $this->assertDatabaseHas('employees', [
            'id' => $current->id,
            'status' => EmployeeStatus::Current,
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
        $current->update(['status' => EmployeeStatus::Suspended]);

        $this->artisan(UpdateEmployeeSuspensions::class);

        $this->assertDatabaseHas('employees', [
            'id' => $current->id,
            'status' => EmployeeStatus::Current,
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
        $current->update(['status' => EmployeeStatus::Suspended]);

        $this->artisan(UpdateEmployeeSuspensions::class);

        $this->assertDatabaseHas('employees', [
            'id' => $current->id,
            'status' => EmployeeStatus::Current,
        ]);
    }

    /** @test */
    // public function command_is_schedulled_for_evey_thirty_minutes()
    // {
    //     $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
    //         ->filter(function ($element) {
    //             return str($element->command)->contains('support:update-ticket-status');
    //         })->first();

    //     $this->assertNotNull($addedToScheduler);
    //     $this->assertEquals('0,30 * * * *', $addedToScheduler->expression);
    // }
}
