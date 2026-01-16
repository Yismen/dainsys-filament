<?php

use App\Console\Commands\SendTicketsExpiredReport;
use App\Console\Commands\UpdateTicketStatus;
use App\Enums\SupportRoles;
use App\Events\TicketCreatedEvent;
use App\Mail\TicketsExpiredMail;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    Event::fake([
        TicketCreatedEvent::class,
    ]);
});

it('is is schedulled daily at 8:15 am', function () {
    $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
        ->filter(function ($element) {
            return str($element->command)->contains('dainsys:send-tickets-expired-report');
        })->first();

    $this->assertNotNull($addedToScheduler);
    $this->assertEquals('15 8 * * *', $addedToScheduler->expression);
});

it('send tickets in status expired', function () {
    $role = Role::firstOrCreate(['name' => SupportRoles::Manager->value]);
    $recipient = User::factory()->create();
    $recipient->assignRole($role);

    $ticket = Ticket::factory()->create();

    $this->travelTo(now()->addDays(50));
    $this->artisan(UpdateTicketStatus::class);
    $this->artisan(SendTicketsExpiredReport::class);

    Mail::assertSent(TicketsExpiredMail::class, function ($mail) use ($ticket, $recipient) {
        return $mail->tickets->contains('id', $ticket->id)
            && $mail->to[0]['address'] === $recipient->email;
    });
});

it('send it only if there is any ticket expired', function () {
    $role = Role::firstOrCreate(['name' => SupportRoles::Manager->value]);
    $recipient = User::factory()->create();
    $recipient->assignRole($role);

    $ticket = Ticket::factory()->create();

    $this->artisan(SendTicketsExpiredReport::class);

    Mail::assertNotSent(TicketsExpiredMail::class);
});
