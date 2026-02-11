<?php

use App\Models\SocialSecurity;

test('social securities model interacts with db table', function (): void {
    $data = SocialSecurity::factory()->make();

    SocialSecurity::create($data->toArray());

    $this->assertDatabaseHas('social_securities', $data->only([
        'employee_id', 'ars_id', 'afp_id', 'number',
    ]));
});

test('social_securities model belongs to model', function (string $modelClass, string $relationMethod): void {
    $socialSecurity = SocialSecurity::factory()->create();

    expect($socialSecurity->$relationMethod)->toBeInstanceOf($modelClass);
    expect($socialSecurity->$relationMethod())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
})->with([
    [\App\Models\Employee::class, 'employee'],
    [\App\Models\Ars::class, 'ars'],
    [\App\Models\Afp::class, 'afp'],
]);
