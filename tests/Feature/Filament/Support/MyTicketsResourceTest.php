<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

it('can render the my tickets page', function (): void {
    /** @var User $user */
    $user = User::factory()->createOne();

    actingAs($user)
        ->get(route('my-tickets-management'))
        ->assertSuccessful()
        ->assertSeeLivewire('my-tickets-management');
});
