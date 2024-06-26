<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Suspension;
use App\Events\SuspensionUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Testing\Fakes\EventFake;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuspensionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function suspensions_model_interacts_with_db_table()
    {
        Mail::fake();

        $data = Suspension::factory()->make();

        Suspension::create($data->toArray());

        $this->assertDatabaseHas('suspensions', $data->only([
            'employee_id', 'suspension_type_id', 'comments'
        ]));
    }

    /** @test */
    public function suspension_model_uses_soft_delete()
    {
        Mail::fake();
        $suspension = Suspension::factory()->create();

        $suspension->delete();

        $this->assertSoftDeleted(Suspension::class, $suspension->only(['id']));
    }

    /** @test */
    public function suspensions_model_belongs_to_employee()
    {
        Mail::fake();
        $suspension = Suspension::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $suspension->employee());
    }

    /** @test */
    public function suspensions_model_belongs_to_suspensionType()
    {
        Mail::fake();
        $suspension = Suspension::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $suspension->suspensionType());
    }

    /** @test */
    public function suspension_model_fires_event_when_created()
    {
        Mail::fake();
        Event::fake();
        $suspension = Suspension::factory()->create();

        Event::assertDispatched(SuspensionUpdated::class);
    }

    /** @test */
    // public function email_is_sent_when_suspension_is_created()
    // {
    //     Mail::fake();
    //     Suspension::factory()->create();

    //     Mail::assertQueued(MailSuspensionUpdated::class);
    // }
}
