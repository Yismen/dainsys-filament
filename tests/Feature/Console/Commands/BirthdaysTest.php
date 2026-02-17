<?php

use App\Console\Commands\Birthdays;
use App\Events\EmployeeHiredEvent;
use App\Events\EmployeeSuspendedEvent;
use App\Events\EmployeeTerminatedEvent;
use App\Mail\BirthdaysMail;
use App\Models\Employee;
use App\Models\Hire;
use Illuminate\Console\Scheduling\Event as SchedulingEvent;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function (): void {
    Event::fake([
        EmployeeHiredEvent::class,
        EmployeeSuspendedEvent::class,
        EmployeeTerminatedEvent::class,
    ]);
    Mail::fake();
    $this->employee = Employee::factory()->create(['date_of_birth' => now()]);

    Hire::factory()->for($this->employee)->create();
});

it('trows exception if report type is not registered in the command', function (): void {
    $this->artisan('dainsys:birthdays', ['invalid' => 'Invalid']);

})->throws(Exception::class);

test('birthdays command run sucessfully with type=', function (string $type): void {
    $this->artisan('dainsys:birthdays', ['type' => $type])
        ->assertSuccessful();
})->with([
    'today',
    'yesterday',
    'tomorrow',
    'this_month',
    'next_month',
    'last_month',
]);

it('runs daily at 4:00 am with type=today', function (string $type, string $expression): void {
    $schedule = app()->make(Schedule::class);

    $command = collect($schedule->events())->first(function (SchedulingEvent $event) use ($type) {
        return stripos($event->command, 'dainsys:birthdays type="'.$type.'"') !== false;
    });

    expect($command->expression)->toEqual($expression);
})->with([
    ['today', '0 4 * * *'],
    ['this_month', '1 4 1 * *'],
]);

test('birthdays command sends email', function (): void {
    Employee::factory()
        ->hasHires()
        ->create();

    $this->artisan(Birthdays::class, ['type' => 'today']);

    Mail::assertQueued(BirthdaysMail::class);
});

test('birthdays command doesnot send email if service is empty', function (): void {
    $this->employee->update(['date_of_birth' => now()->addDay()]);

    $this->artisan(Birthdays::class, ['type' => 'today']);

    Mail::assertNotQueued(BirthdaysMail::class);
});
