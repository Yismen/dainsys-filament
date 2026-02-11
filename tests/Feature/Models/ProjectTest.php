<?php

use App\Models\Campaign;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Project;

beforeEach(function (): void {
    \Illuminate\Support\Facades\Event::fake([
        \App\Events\EmployeeHiredEvent::class,
    ]);
});

test('projects model interacts with db table', function (): void {
    $data = Project::factory()->make();

    Project::create($data->toArray());

    $this->assertDatabaseHas('projects', $data->only([
        'name', 'client_id', 'description',
    ]));
});

test('projects model belongs to client', function (): void {
    $project = Project::factory()
        ->create();

    expect($project->client)->toBeInstanceOf(Client::class);
    expect($project->client())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});

test('projects model has many campaigns', function (): void {
    $project = Project::factory()
        ->has(Campaign::factory())
        ->create();

    expect($project->campaigns->first())->toBeInstanceOf(Campaign::class);
    expect($project->campaigns())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('projects model has many hires', function (): void {
    $project = Project::factory()
        ->hasHires()
        ->create();

    expect($project->hires->first())->toBeInstanceOf(\App\Models\Hire::class);
    expect($project->hires())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('projects model has many employees', function (): void {
    $project = Project::factory()
        ->has(
            Hire::factory()
                ->has(Employee::factory())
        )
        ->create();

    expect($project->employees->first())->toBeInstanceOf(Employee::class);
    expect($project->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
