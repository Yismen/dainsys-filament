<?php

use App\Models\Employee;
use App\Models\Site;

it('logs changes for created event', function (): void {
    $employee = Employee::factory()->create();

    $this->assertDatabaseHas('activity_log', [
        'subject_type' => Employee::class,
        'subject_id' => $employee->id,
        'description' => 'created',
    ]);
});

it('logs changes for updated event', function (): void {
    $employee = Employee::factory()->create();

    $employee->first_name = 'UpdatedName';
    $employee->save();

    $this->assertDatabaseHas('activity_log', [
        'subject_type' => Employee::class,
        'subject_id' => $employee->id,
        'description' => 'updated',
    ]);
});

it('logs changes for deleted event', function (): void {
    $employee = Employee::factory()->create();

    $employee->delete();

    $this->assertDatabaseHas('activity_log', [
        'subject_type' => Employee::class,
        'subject_id' => $employee->id,
        'description' => 'deleted',
    ]);
});

it('logs changes for related models event', function (): void {
    $employee = Employee::factory()->create();
    $site = Site::factory()->create();

    $employee->site_id = $site->id;
    $employee->save();

    $this->assertDatabaseHas('activity_log', [
        'subject_type' => Employee::class,
        'subject_id' => $employee->id,
        'description' => 'updated',
    ]);
});

it('does not log changes when only ignored attributes are changed', function (): void {
    $employee = Employee::factory()->create();

    $employee->created_at = now()->addMinutes(5);
    $employee->updated_at = now()->addMinutes(5);
    $employee->save();

    $this->assertDatabaseMissing('activity_log', [
        'subject_type' => Employee::class,
        'subject_id' => $employee->id,
        'description' => 'updated',
    ]);
});
