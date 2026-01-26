<?php

use App\Models\Mailable;
use App\Models\Supervisor;
use App\Models\User;

test('users model interacts with db table', function () {
    $data = User::factory()->create();

    $this->assertDatabaseHas('users', $data->only([
        'name',
        'email',
        'email_verified_at',
        'remember_token',
    ]));
});

test('users model belongs to many mailables', function () {
    $user = User::factory()
        ->has(Mailable::factory(), 'mailables')
        ->create();

    expect($user->mailables())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
    expect($user->mailables->first())->toBeInstanceOf(Mailable::class);
});

test('users model has one supervisor', function () {
    $user = User::factory()->create();
    Supervisor::factory()->create(['user_id' => $user->id]);

    expect($user->supervisor)->toBeInstanceOf(Supervisor::class);
    expect($user->supervisor())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class);
});
