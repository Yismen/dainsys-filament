<?php

namespace Tests\Feature\Events;

use Tests\TestCase;
use App\Models\Ticket;
use App\Mail\TicketCreatedMail;
use App\Models\TicketDepartment;
use App\Events\TicketCreatedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendTicketCreatedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketCreatedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            TicketCreatedEvent::class
        ]);

        $ticket = Ticket::factory()->create();

        Event::assertDispatched(TicketCreatedEvent::class);
        Event::assertListening(
            TicketCreatedEvent::class,
            SendTicketCreatedMail::class
        );
    }

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
}
