<?php

use App\Models\LoginName;

test('login names model interacts with db table', function () {
    $data = LoginName::factory()->make();

    LoginName::create($data->toArray());

    $this->assertDatabaseHas('login_names', $data->only([
        'login_name', 'employee_id',
    ]));
});

test('login names model belongs to one employee', function () {
    $login_name = LoginName::factory()->create();

    expect($login_name->employee)->toBeInstanceOf(\App\Models\Employee::class);
    expect($login_name->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
