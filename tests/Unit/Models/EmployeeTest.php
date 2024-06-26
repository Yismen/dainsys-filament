<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Employee;
use App\Events\EmployeeCreated;
use App\Mail\MailEmployeeCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Mail\EmployeeCreated as EmployeeCreatedMail;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function employee_model_interacts_with_employees_table()
    {
        Event::fake();
        $data = Employee::factory()->make();

        Employee::create($data->toArray());

        $this->assertDatabaseHas('employees', $data->only([
            'first_name',
            'second_first_name',
            'last_name',
            'second_last_name',
            'personal_id',
            // 'full_name',
            // 'hired_at',
            // 'date_of_birth',
            'cellphone',
            'status',
            'marriage',
            'punch',
            'gender',
            'kids',
            'site_id',
            'project_id',
            'position_id',
            'citizenship_id',
            'supervisor_id',
            'afp_id',
            'ars_id',
        ]));
    }

    /** @test */
    public function employee_model_uses_soft_delete()
    {
        Mail::fake();
        $employee = Employee::factory()->create();

        $employee->delete();

        $this->assertSoftDeleted(Employee::class, $employee->only('id'));
    }

    /** @test */
    public function employees_model_morph_one_information()
    {
        $employee = Employee::factory()->createQuietly();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class, $employee->information());
    }

    /** @test */
    public function employees_model_belongs_to_site()
    {
        $employee = Employee::factory()->createQuietly();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $employee->site());
    }

    /** @test */
    public function employees_model_belongs_to_project()
    {
        $employee = Employee::factory()->createQuietly();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $employee->project());
    }

    /** @test */
    public function employees_model_belongs_to_position()
    {
        $employee = Employee::factory()->createQuietly();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $employee->position());
    }

    /** @test */
    public function employees_model_belongs_to_department()
    {
        $employee = Employee::factory()->createQuietly();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $employee->department());
    }

    /** @test */
    public function employees_model_belongs_to_citizenship()
    {
        $employee = Employee::factory()->createQuietly();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $employee->citizenship());
    }

    /** @test */
    public function employees_model_belongs_to_supervisor()
    {
        $employee = Employee::factory()->createQuietly();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $employee->supervisor());
    }

    /** @test */
    public function employees_model_belongs_to_afp()
    {
        $employee = Employee::factory()->createQuietly();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $employee->afp());
    }

    /** @test */
    public function employees_model_belongs_to_ars()
    {
        $employee = Employee::factory()->createQuietly();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $employee->ars());
    }

    /** @test */
    public function employees_model_has_many_terminations()
    {
        $employee = Employee::factory()->createQuietly();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $employee->terminations());
    }

    /** @test */
    public function employees_model_has_many_loginNames()
    {
        $employee = Employee::factory()->createQuietly();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $employee->loginNames());
    }

    /** @test */
    public function employees_model_has_many_suspensions()
    {
        $employee = Employee::factory()->createQuietly();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $employee->suspensions());
    }

    /** @test */
    public function employee_model_fires_event_when_created()
    {
        Mail::fake();
        Event::fake();
        $employee = Employee::factory()->create();

        Event::assertDispatched(EmployeeCreated::class);
    }

    /** @test */
    public function employee_model_update_full_name_when_saved()
    {
        Mail::fake();
        $employee = Employee::factory()->create();

        $name = trim(
            join(' ', array_filter([
                $employee->first_name,
                $employee->second_first_name,
                $employee->last_name,
                $employee->second_last_name,
            ]))
        );

        $this->assertDatabaseHas('employees', ['full_name' => $name]);
    }

    /** @test */
    // public function email_is_sent_when_employee_is_created()
    // {
    //     Mail::fake();
    //     Employee::factory()->create();

    //     Mail::assertQueued(EmployeeCreatedMail::class);
    // }
}
