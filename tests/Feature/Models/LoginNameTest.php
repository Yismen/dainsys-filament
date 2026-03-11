<?php

use App\Models\Employee;
use App\Models\LoginName;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('login names model interacts with db table', function (): void {
    $data = LoginName::factory()->make();

    LoginName::create($data->toArray());

    $this->assertDatabaseHas('login_names', $data->only([
        'login_name', 'employee_id',
    ]));
});

test('login names model belongs to one employee', function (): void {
    $login_name = LoginName::factory()->create();

    expect($login_name->employee)->toBeInstanceOf(Employee::class);
    expect($login_name->employee())->toBeInstanceOf(BelongsTo::class);
});
