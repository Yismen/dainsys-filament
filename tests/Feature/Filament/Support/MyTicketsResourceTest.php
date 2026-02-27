<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

it('can render the my tickets page', function (): void {
    $user = User::factory()->create();
    actingAs($user)
        ->get(route('my-tickets-management'))
        ->assertSuccessful()
        ->assertSeeLivewire('my-tickets-management');
});
