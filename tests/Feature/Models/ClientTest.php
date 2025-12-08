<?php

use App\Models\Client;

test('clients model interacts with db table', function () {
    $data = Client::factory()->make();

    Client::create($data->toArray());

    $this->assertDatabaseHas('clients', $data->only([
        'name', 'person_of_contact', 'description'
    ]));
});

test('client model uses soft delete', function () {
    $client = Client::factory()->create();

    $client->delete();

    $this->assertSoftDeleted(Client::class, [
        'id' => $client->id
    ]);
});

test('clients model morph one information', function () {
    $client = Client::factory()->create();

    expect($client->information)->toBeInstanceOf(\App\Models\Information::class);
    expect($client->information())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class);
});

test('clients model has many employees', function () {
    $client = Client::factory()->create();

    expect($client->employees->first())->toBeInstanceOf(\App\Models\Employee::class);
    expect($client->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
