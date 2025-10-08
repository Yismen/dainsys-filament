<?php

use App\Models\LoginName;
use Illuminate\Support\Facades\Mail;

test('login names model interacts with db table', function () {
    Mail::fake();
    $data = LoginName::factory()->make();

    LoginName::create($data->toArray());

    $this->assertDatabaseHas('login_names', $data->only([
        'login_name', 'employee_id'
    ]));
});

test('login name model uses soft delete', function () {
    Mail::fake();
    $login_name = LoginName::factory()->create();

    $login_name->delete();

    $this->assertSoftDeleted(LoginName::class, $login_name->only(['id']));
});

test('login names model belongs to one employee', function () {
    Mail::fake();
    $login_name = LoginName::factory()->create();

    expect($login_name->employee())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
});
