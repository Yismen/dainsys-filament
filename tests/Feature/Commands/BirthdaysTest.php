<?php

namespace Tests\Feature\Console\Commands;

use Tests\TestCase;
use App\Models\Employee;
use App\Console\Commands\Birthdays;
use Illuminate\Support\Facades\Mail;
use App\Mail\Birthdays as MailBirthdays;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BirthdaysTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function birthdays_command_run_sucessfully()
    {
        $this->withoutExceptionHandling();
        Mail::fake();
        $this->artisan('dainsys:birthdays', ['type' => 'today'])
            ->assertSuccessful();
    }

    /** @test */
    public function birthdays_command_sends_email()
    {
        Mail::fake();
        $employee1 = Employee::factory()->current()->create(['date_of_birth' => now()]);

        $this->artisan(Birthdays::class, ['type' => 'today']);

        Mail::assertQueued(MailBirthdays::class);
    }

    /** @test */
    public function birthdays_command_doesnot_send_email_if_service_is_empty()
    {
        Mail::fake();
        $employee1 = Employee::factory()->current()->create(['date_of_birth' => now()->addDay()]);

        $this->artisan(Birthdays::class, ['type' => 'today']);

        Mail::assertNotQueued(MailBirthdays::class);
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
