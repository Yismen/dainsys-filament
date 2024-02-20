<?php

namespace Tests\Unit\Events;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Events\EmployeeCreated;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendEmployeeCreatedEmail;

class EmployeeCreatedTest extends TestCase
{
    /** @test */
    public function employee_saved_is_listened_ty_update_full_name()
    {
        Event::fake();
        Event::assertListening(EmployeeCreated::class, SendEmployeeCreatedEmail::class);
    }
}
