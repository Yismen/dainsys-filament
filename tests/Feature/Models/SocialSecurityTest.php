<?php

use App\Models\Afp;
use App\Models\Ars;
use App\Models\Employee;
use App\Models\SocialSecurity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    expect($socialSecurity->$relationMethod())->toBeInstanceOf(BelongsTo::class);
})->with([
    [Employee::class, 'employee'],
    [Ars::class, 'ars'],
    [Afp::class, 'afp'],
]);
