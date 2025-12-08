<?php

use App\Models\Site;

test('sites model interacts with db table', function () {
    $data = Site::factory()->make();

    Site::create($data->toArray());

    $this->assertDatabaseHas('sites', $data->only([
        'name', 'person_of_contact', 'description'
    ]));
});

test('site model uses soft delete', function () {
    $site = Site::factory()->create();

    $site->delete();

    $this->assertSoftDeleted(Site::class, [
        'id' => $site->id
    ]);
});

test('sites model morph one information', function () {
    $site = Site::factory()->create();

    expect($site->information())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphOne::class);
});

test('sites model has many employees', function () {
    $site = Site::factory()->create();

    expect($site->employees())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
