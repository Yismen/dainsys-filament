<?php

use App\Models\Employee;
use App\Models\Suspension;
use App\Enums\EmployeeStatus;
use Illuminate\Support\Facades\Mail;
use App\Console\Commands\EmployeesSuspended;

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
});

test('employees suspended does not sends email if there is not employees suspended', function () {
    Mail::fake();
    $current = Employee::factory()->current()->createQuietly();

    $this->artisan(EmployeesSuspended::class);

    Mail::assertNotQueued(\App\Mail\EmployeesSuspended::class);
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
