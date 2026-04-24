<?php

use App\Console\Commands\SendSuspendedEmployeesEmail;
use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeSuspendedEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Mail\SuspendedEmployeesMail;
use App\Models\Employee;
use App\Models\Mailable;
use App\Models\Suspension;
use App\Models\User;
use App\Notifications\Reports\SuspendedEmployeesReportNotification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

beforeEach(function (): void {
    Event::fake([
        EmployeeSuspendedEvent::class,
        EmployeeHiredEvent::class,
        EmployeeTerminatedEvent::class,
    ]);

    Notification::fake();
});

test('employees suspended run sucessfully', function (): void {
    $this->artisan('dainsys:send-suspended-employees-email')
        ->assertSuccessful();
});

test('command is schedulled for daily at 305 am', function (): void {

    $command = collect(app()->make(Schedule::class)->events())
        ->first(function ($element) {
            return str($element->command)->contains('dainsys:send-suspended-employees-email');
        });

    expect($command)->not()->toBeNull();
    expect($command->expression)->toBe('5 3 * * *');
});

test('employees suspended sends email', function (): void {
    $mailable = Mailable::query()->firstOrCreate(
        ['name' => SuspendedEmployeesMail::class],
        ['description' => SuspendedEmployeesMail::class]
    );
    $recipient = User::factory()->create();
    $recipient->mailables()->attach($mailable);
    Cache::forget('mailing_subscriptions_for_mailable_'.SuspendedEmployeesMail::class);

    $employee = Employee::factory()
        ->hasHires()
        ->create();
    Suspension::factory()->create([
        'employee_id' => $employee->id,
        'starts_at' => now(),
        'ends_at' => now()->addDay(),
    ]);

    $this->artisan(SendSuspendedEmployeesEmail::class);

    Notification::assertSentTo($recipient, SuspendedEmployeesReportNotification::class);
});

test('employees suspended does not sends email if there is not employees suspended', function (): void {
    $employee = Employee::factory()
        ->hasHires()
        ->create();

    $this->artisan(SendSuspendedEmployeesEmail::class);

    Notification::assertNothingSent();
});

// /** @test */
// public function command_is_schedulled_for_evey_thirty_minutes()
// {
//     $command = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
//         ->filter(function ($element) {
//             return str($element->command)->contains('support:update-ticket-status');
//         })->first();
//     $this->assertNotNull($command);
//     $this->assertEquals('0,30 * * * *', $command->expression);
// }
