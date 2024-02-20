<?php

namespace Tests\Unit\Events;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Events\TerminationCreated;
use App\Listeners\TerminateEmployee;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendEmployeeTerminatedEmail;

class TerminationCreatedTest extends TestCase
{

    /** @test */
    public function employee_saved_is_listened_ty_update_full_name()
    {
        Event::fake();
        Event::assertListening(TerminationCreated::class, TerminateEmployee::class);
        Event::assertListening(TerminationCreated::class, SendEmployeeTerminatedEmail::class);
    }
}
