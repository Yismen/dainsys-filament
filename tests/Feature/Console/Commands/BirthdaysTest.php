<?php

use App\Console\Commands\Birthdays;
use App\Mail\Birthdays as MailBirthdays;
use App\Models\Employee;
use App\Models\Hire;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Event::fake();
    Mail::fake();
    $this->employee = Employee::factory()->current()->create(['date_of_birth' => now()]);

    Hire::factory()->for($this->employee)->create();
});

test('birthdays command run sucessfully', function () {
    $this->withoutExceptionHandling();
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

test('birthdays command sends email', function () {
    $this->artisan(Birthdays::class, ['type' => 'today']);

    Mail::assertQueued(MailBirthdays::class);
});

test('birthdays command doesnot send email if service is empty', function () {
    $this->employee->update(['date_of_birth' => now()->addDay()]);

    $this->artisan(Birthdays::class, ['type' => 'today']);

    Mail::assertNotQueued(MailBirthdays::class);
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
