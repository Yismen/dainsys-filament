<?php

namespace Tests\Unit\Events;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Events\EmployeeSaved;
use Illuminate\Support\Facades\Event;
use App\Listeners\UpdateEmployeeFullName;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Listeners\UpdateFullName;

class EmployeeSavedTest extends TestCase
{
    /** @test */
    public function employee_saved_is_listened_ty_update_full_name()
    {
        Event::fake();
        Event::assertListening(EmployeeSaved::class, UpdateEmployeeFullName::class);
    }
}
