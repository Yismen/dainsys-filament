<?php

use App\Models\Ticket;
use App\Models\TicketDepartment;
use Illuminate\Database\Eloquent\SoftDeletes;

test('departments model interacts with db table', function () {
    $data = TicketDepartment::factory()->make();

    TicketDepartment::create($data->toArray());

    $this->assertDatabaseHas('ticket_departments', $data->only([
        'name',
        // 'ticket_prefix',
        'description'
    ]));
});

test('departments model has many tickets', function () {
    $department = TicketDepartment::factory()->create();

    Ticket::factory()->create(['department_id' => $department->id]);

    expect($department->tickets())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($department->tickets->first())->toBeInstanceOf(Ticket::class);
});

test('department model set tickets completed attribute', function () {
    $department = TicketDepartment::factory()->create();

    Ticket::factory()->completed()->create(['department_id' => $department->id]);
    Ticket::factory()->create(['department_id' => $department->id]);
    Ticket::factory()->create();

    expect($department->tickets_completed)->toEqual(1);
});

test('department model set tickets incompleted attribute', function () {
    $department = TicketDepartment::factory()->create();
    Ticket::factory()->completed()->create(['department_id' => $department->id]);
    Ticket::factory()->create(['department_id' => $department->id]);

    expect($department->tickets_incompleted)->toEqual(1);
});

test('department model set completion rate attribute', function () {
    $department = TicketDepartment::factory()->create();
    Ticket::factory()->completed()->create(['department_id' => $department->id]);
    Ticket::factory()->create(['department_id' => $department->id, 'completed_at' => now()->addDays(100)]);

    expect($department->compliance_rate)->toEqual(0.5);
});

test('department model set compliance rate attribute', function () {
    $department = TicketDepartment::factory()->create();
    Ticket::factory()->compliant()->create(['department_id' => $department->id]);
    Ticket::factory()->noncompliant()->create(['department_id' => $department->id]);

    expect($department->compliance_rate)->toEqual(0.5);
});

test('departments model parse prefix to all caps and dash at the end', function () {
    $department = TicketDepartment::factory()->create(['name' => 'craziness']);

    $this->assertDatabaseHas(TicketDepartment::class, [
        'ticket_prefix' => 'CRAZ-'
    ]);
});

test('department model sets ticket prefix correctly', function () {
    $department = TicketDepartment::factory()->create(['name' => 'administration', 'ticket_prefix' => null]);

    $this->assertDatabaseHas(TicketDepartment::class, [
        'id' => $department->id,
        'ticket_prefix' => 'ADMI-',
    ]);
});

test('department model sets ticket prefix correctly when name is two words or more', function () {
    $department = TicketDepartment::factory()->create(['name' => 'admini service', 'ticket_prefix' => null]);

    $this->assertDatabaseHas(TicketDepartment::class, [
        'id' => $department->id,
        'ticket_prefix' => 'ADSE-',
    ]);
});

test('department model sets ticket prefix to a new value when prefix exist already', function () {
    $department_1 = TicketDepartment::factory()->create(['name' => 'admini']);
    $department_2 = TicketDepartment::factory()->create(['name' => 'administration']);

    $this->assertDatabaseHas(TicketDepartment::class, [
        'id' => $department_1->id,
        'ticket_prefix' => 'ADMI-',
    ]);

    $this->assertDatabaseMissing(TicketDepartment::class, [
        'id' => $department_2->id,
        'ticket_prefix' => 'ADMI-',
    ]);
});
