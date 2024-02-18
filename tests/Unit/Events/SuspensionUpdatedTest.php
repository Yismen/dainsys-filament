<?php

namespace Tests\Unit\Events;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Events\SuspensionUpdated;
use App\Listeners\SuspendEmployee;
use Illuminate\Support\Facades\Event;

class SuspensionUpdatedTest extends TestCase
{
    /** @test */
    public function suspension_saved_is_listened_ty_update_suspension()
    {
        Event::fake();
        Event::assertListening(SuspensionUpdated::class, SuspendEmployee::class);
        // Event::assertListening(SuspensionUpdated::class, SendEmployeeSuspendedEmail::class);
    }
}
