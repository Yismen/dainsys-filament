<?php

use App\Models\Campaign;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Hire;
use App\Models\Production;
use App\Models\Project;

test('clients model interacts with db table', function () {
    $data = Client::factory()->make();

    Client::create($data->toArray());

    $this->assertDatabaseHas('clients', $data->only([
        'name', 'person_of_contact', 'phone', 'email', 'website', 'description',
    ]));
});

test('clients model has many projects', function () {
    $client = Client::factory()
        ->has(Project::factory(), 'projects')
        ->create();

    expect($client->projects->first())->toBeInstanceOf(Project::class);
    expect($client->projects())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

test('clients model has many campaigns thru projects', function () {
    $client = Client::factory()
        ->has(Project::factory()->has(Campaign::factory()), 'projects')
        ->create();

    expect($client->campaigns->first())->toBeInstanceOf(Campaign::class);
    expect($client->campaigns())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
});

// test('clients model has many productions thru campaign', function () {
//     $client = Client::factory()
//         ->has(Project::factory()->has(Campaign::factory()))
//         ->create();

//     expect($client->productions->first())->toBeInstanceOf(Production::class);
//     expect($client->productions())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
// });

// test('clients model has many employees thru projects and hires', function () {
//     $client = Client::factory()
//         ->has(Project::factory()->has(Hire::factory()), 'projects')
//         ->create();

//     expect($client->employees->first())->toBeInstanceOf(Employee::class);
//     expect($client->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasManyThrough::class);
// });
