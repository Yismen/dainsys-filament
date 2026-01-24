<?php

use App\Models\User;
use App\Models\Mailable;
use App\Models\MailingSubscription;

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
