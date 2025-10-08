<?php

use App\Models\Project;

test('projects model interacts with db table', function () {
    $data = Project::factory()->make();

    Project::create($data->toArray());

    $this->assertDatabaseHas('projects', $data->only([
        'name', 'description'
    ]));
});

test('project model uses soft delete', function () {
    $project = Project::factory()->create();

    $project->delete();

    $this->assertSoftDeleted(Project::class, $project->only('id'));
});

test('projects model has many employees', function () {
    $project = Project::factory()->create();

    expect($project->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('projects model has many campaigns', function () {
    $project = Project::factory()->create();

    expect($project->campaigns())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('projects model morph one information', function () {
    $project = Project::factory()->create();

    expect($project->information())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class);
});
