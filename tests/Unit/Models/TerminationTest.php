<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Termination;
use App\Events\TerminationCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TerminationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function terminations_model_interacts_with_db_table()
    {
        Mail::fake();
        $data = Termination::factory()->make();

        Termination::create($data->toArray());

        $this->assertDatabaseHas('terminations', $data->only([
            'employee_id', 'termination_type_id', 'termination_reason_id', 'comments', 'rehireable'
        ]));
    }

    /** @test */
    public function termination_model_uses_soft_delete()
    {
        Mail::fake();
        $termination = Termination::factory()->create();


        $termination->delete();

        $this->assertSoftDeleted(Termination::class, $termination->only(['id', 'employee_id']));
    }

    /** @test */
    public function terminations_model_belongs_to_employee()
    {
        Mail::fake();
        $termination = Termination::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $termination->employee());
    }

    /** @test */
    public function terminations_model_belongs_to_terminationType()
    {
        Mail::fake();
        $termination = Termination::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $termination->terminationType());
    }

    /** @test */
    public function terminations_model_belongs_to_terminationReason()
    {
        Mail::fake();
        $termination = Termination::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $termination->terminationReason());
    }

    /** @test */
    public function termination_model_fires_event_when_created()
    {
        Mail::fake();
        Event::fake();
        $termination = Termination::factory()->create();

        Event::assertDispatched(TerminationCreated::class);
    }

    /** @test */
    // public function email_is_sent_when_termination_is_created()
    // {
    //     Mail::fake();
    //     Termination::factory()->create();

    //     Mail::assertQueued(MailTerminationCreated::class);
    // }
}
