<?php

use App\Console\Commands\Birthdays;
use App\Events\EmployeeHiredEvent;
use App\Events\SuspensionUpdated;
use App\Events\TerminationCreated;
use App\Mail\Birthdays as MailBirthdays;
use App\Models\Employee;
use App\Models\Hire;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Event::fake([
        EmployeeHiredEvent::class,
        SuspensionUpdated::class,
        TerminationCreated::class,
    ]);
    Mail::fake();
    $this->employee = Employee::factory()->create(['date_of_birth' => now()]);

    Hire::factory()->for($this->employee)->create();
});

test('birthdays command run sucessfully', function () {
    $this->artisan('dainsys:birthdays', ['type' => 'today'])
        ->assertSuccessful();
});

test('command is schedulled for daily at 401 am', function () {
    $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->filter(function ($element) {
            return str($element->command)->contains('dainsys:birthdays');
        })->first();

    expect($addedToScheduler)->not->toBeNull();
    expect($addedToScheduler->expression)->toEqual('0 4 * * *');
});

it('trows exception if report type is not registered in the command', function () {
    $this->artisan('dainsys:birthdays', ['invalid' => 'Invalid']);

})->throws(Exception::class);

test('birthdays command sends email', function () {
    Employee::factory()
        ->hasHires()
        ->create();

    $this->artisan(Birthdays::class, ['type' => 'today']);

    Mail::assertQueued(MailBirthdays::class);
});

test('birthdays command doesnot send email if service is empty', function () {
    $this->employee->update(['date_of_birth' => now()->addDay()]);

    $this->artisan(Birthdays::class, ['type' => 'today']);

    Mail::assertNotQueued(MailBirthdays::class);
});
