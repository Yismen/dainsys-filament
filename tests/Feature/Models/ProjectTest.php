<?php

use App\Events\EmployeeHiredEvent;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    Event::fake([
        EmployeeHiredEvent::class,
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
    expect($project->client())->toBeInstanceOf(BelongsTo::class);
});

test('projects model has many campaigns', function (): void {
    $project = Project::factory()
        ->has(Campaign::factory())
        ->create();

    expect($project->campaigns->first())->toBeInstanceOf(Campaign::class);
    expect($project->campaigns())->toBeInstanceOf(HasMany::class);
});

test('projects model has many hires', function (): void {
    $project = Project::factory()
        ->hasHires()
        ->create();

    expect($project->hires->first())->toBeInstanceOf(Hire::class);
    expect($project->hires())->toBeInstanceOf(HasMany::class);
});

test('projects model has many employees', function (): void {
    $project = Project::factory()
        ->has(
            Hire::factory()
                ->has(Employee::factory())
        )
        ->create();

    expect($project->employees->first())->toBeInstanceOf(Employee::class);
    expect($project->employees())->toBeInstanceOf(HasMany::class);
});
