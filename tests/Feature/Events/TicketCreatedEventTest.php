<?php

use App\Events\TicketCreatedEvent;
use App\Listeners\SendTicketCreatedMail;
use App\Mail\TicketCreatedMail;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

test('event is dispatched', function () {
    Event::fake([
        TicketCreatedEvent::class,
    ]);

    $ticket = Ticket::factory()->create();

    Event::assertDispatched(TicketCreatedEvent::class);
    Event::assertListening(
        TicketCreatedEvent::class,
        SendTicketCreatedMail::class
    );
});

/** @test */
// public function email_is_sent()
// {
//     Mail::fake();
//     $superAdmin = $this->supportSuperAdminUser();
//     $department = TicketDepartment::factory()->create();
//     $department_admin = $this->departmentAdminUser($department);
//     $ticket = Ticket::factory()->create(['department_id' => $department->id]);
//     Mail::assertQueued(TicketCreatedMail::class);
// }
