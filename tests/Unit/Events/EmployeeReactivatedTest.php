<?php

namespace Tests\Unit\Events;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Events\EmployeeReactivated;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendEmployeeReactivatedEmail;

class EmployeeReactivatedTest extends TestCase
{
    /** @test */
    public function employee_saved_is_listened_ty_update_full_name()
    {
        Event::fake();
        Event::assertListening(EmployeeReactivated::class, SendEmployeeReactivatedEmail::class);
    }
}
