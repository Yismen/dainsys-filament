<?php

use App\Console\Commands\SendSuspendedEmployeesEmail;
use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeSuspendedEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Mail\SuspendedEmployeesMail;
use App\Models\Employee;
use App\Models\Suspension;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Event::fake([
        EmployeeSuspendedEvent::class,
        EmployeeHiredEvent::class,
        EmployeeTerminatedEvent::class,
    ]);

    Mail::fake();
});

test('employees suspended run sucessfully', function (): void {
    $this->artisan('dainsys:send-suspended-employees-email')
        ->assertSuccessful();
});

test('command is schedulled for daily at 305 am', function (): void {
     $this->app->make(\Illuminate\Contracts\Console\Kernel::class);

    $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->first(function ($element) {
            return str($element->command)->contains('dainsys:send-suspended-employees-email');
        });
        
    expect($addedToScheduler->expression)->toEqual('5 3 * * *');
});

test('employees suspended sends email', function (): void {
    $employee = Employee::factory()
        ->hasHires()
        ->create();
    Suspension::factory()->create([
        'employee_id' => $employee->id,
        'starts_at' => now(),
        'ends_at' => now()->addDay(),
    ]);

    $this->artisan(SendSuspendedEmployeesEmail::class);

    Mail::assertQueued(SuspendedEmployeesMail::class);
});

test('employees suspended does not sends email if there is not employees suspended', function (): void {
    $employee = Employee::factory()
        ->hasHires()
        ->create();

    $this->artisan(SendSuspendedEmployeesEmail::class);

    Mail::assertNotQueued(SuspendedEmployeesMail::class);
});

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
