<?php

use App\Models\Information;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Mail;

test('information model interacts with db table', function () {
    $data = Information::factory()->create();

    $this->assertDatabaseHas('informations', [
        'id' => $data->id,
        'phone' => $data->phone,
        'email' => $data->email,
        'address' => $data->address,
    ]);
});

test('information model morph to informationable', function () {
    $information = Information::factory()->create();

    expect($information->informationable())->toBeInstanceOf(MorphTo::class);
});

test('information model morph relationship to informationable', function ($modelClass) {
    $relationship = $modelClass::factory()->create();

    $data = [
        'phone' => 'phone',
        'address' => 'address',
        'email' => 'email',
        // 'photos' => [],
    ];

    $relationship->information()->create($data);

    $this->assertDatabaseHas('informations', $data);
    expect($relationship->information->informationable)->toBeInstanceOf($modelClass);
    expect($relationship->information->informationable())->toBeInstanceOf(MorphTo::class);
})->with([
    // \App\Models\Employee::class,
    // \App\Models\Site::class,
    // \App\Models\Bank::class,
    // \App\Models\Ars::class,
    // \App\Models\Afp::class,
    // \App\Models\Project::class,
    // \App\Models\Client::class,
    // \App\Models\Supervisor::class,
]);
