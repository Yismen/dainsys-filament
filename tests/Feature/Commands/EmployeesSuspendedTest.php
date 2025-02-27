<?php

namespace Tests\Feature\Console\Commands;

use Tests\TestCase;
use App\Models\Employee;
use App\Models\Suspension;
use App\Enums\EmployeeStatus;
use Illuminate\Support\Facades\Mail;
use App\Console\Commands\EmployeesSuspended;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeesSuspendedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function employees_suspended_run_sucessfully()
    {
        $this->artisan('dainsys:employees-suspended')
            ->assertSuccessful();
    }

    /** @test */
    public function employees_suspended_sends_email()
    {
        Mail::fake();
        $current = Employee::factory()->createQuietly();
        Suspension::factory()->createQuietly([
            'employee_id' => $current->id,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);
        $current->update(['status' => EmployeeStatus::Suspended]);

        $this->artisan(EmployeesSuspended::class);

        Mail::assertQueued(\App\Mail\EmployeesSuspended::class);
    }

    /** @test */
    public function employees_suspended_does_not_sends_email_if_there_is_not_employees_suspended()
    {
        Mail::fake();
        $current = Employee::factory()->current()->createQuietly();

        $this->artisan(EmployeesSuspended::class);

        Mail::assertNotQueued(\App\Mail\EmployeesSuspended::class);
    }


    // /** @test */
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
