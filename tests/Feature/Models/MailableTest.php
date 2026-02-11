<?php

use App\Models\Mailable;
use App\Models\User;

test('mailables model interacts with db table', function (): void {
    $mailable = Mailable::factory()->make();

    Mailable::create($mailable->toArray());

    $this->assertDatabaseHas('mailables', $mailable->only([
        'name', 'description',
    ]));
});

test('mailables model belongs to many users', function (): void {
    $mailable = Mailable::factory()
        ->has(User::factory())
        ->create();

    expect($mailable->users->first())->toBeInstanceOf(User::class);
    expect($mailable->users())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
});
