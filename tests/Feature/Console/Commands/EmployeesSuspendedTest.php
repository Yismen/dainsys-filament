<?php

use App\Models\Employee;
use App\Models\Suspension;
use App\Enums\EmployeeStatuses;
use App\Events\SuspensionUpdatedEvent;
use App\Events\EmployeeHiredEvent;
use App\Events\TerminationCreatedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Console\Commands\EmployeesSuspended;
use App\Mail\EmployeesSuspendedMail;

beforeEach(function () {
    Event::fake([
        SuspensionUpdatedEvent::class,
        EmployeeHiredEvent::class,
        TerminationCreatedEvent::class,
    ]);

    Mail::fake();
});

test('employees suspended run sucessfully', function () {
    $this->artisan('dainsys:employees-suspended')
        ->assertSuccessful();
});

test('command is schedulled for daily at 305 am', function () {
    $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->filter(function ($element) {
            return str($element->command)->contains('dainsys:employees-suspended');
        })->first();

    expect($addedToScheduler)->not->toBeNull();
    expect($addedToScheduler->expression)->toEqual('5 3 * * *');
});

test('employees suspended sends email', function () {
    $employee = Employee::factory()
        ->hasHires()
        ->create();
        Suspension::factory()->create([
            'employee_id' => $employee->id,
            'starts_at' => now(),
            'ends_at' => now()->addDay(),
        ]);

        $this->artisan(EmployeesSuspended::class);

    Mail::assertQueued(EmployeesSuspendedMail::class);
});

test('employees suspended does not sends email if there is not employees suspended', function () {
    $employee = Employee::factory()
        ->hasHires()
        ->create();

    $this->artisan(EmployeesSuspended::class);

    Mail::assertNotQueued(EmployeesSuspendedMail::class);
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
