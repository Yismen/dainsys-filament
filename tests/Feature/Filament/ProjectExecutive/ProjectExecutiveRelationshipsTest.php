<?php

use App\Models\Project;
use App\Models\User;

it('links a project to its manager', function (): void {
    $manager = User::factory()->create();
    $project = Project::factory()->create([
        'manager_id' => $manager->id,
    ]);

    expect($project->manager)->not->toBeNull()
        ->and($project->manager->is($manager))->toBeTrue();
});

it('returns managed projects for a user', function (): void {
    $manager = User::factory()->create();
    $managedProject = Project::factory()->create([
        'manager_id' => $manager->id,
    ]);

    Project::factory()->create();

    expect($manager->managedProjects()->pluck('id')->toArray())
        ->toContain($managedProject->id)
        ->toHaveCount(1);
});

it('allows project without manager', function (): void {
    $project = Project::factory()->create([
        'manager_id' => null,
    ]);

    expect($project->manager)->toBeNull();
});
