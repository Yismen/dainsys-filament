<?php

use App\Models\User;

it('renders the notification bell for authenticated users on landing layout pages', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('my-subscriptions'))
        ->assertSuccessful()
        ->assertSee('aria-label="Open notifications"', false);
});

it('does not render the notification bell for guests', function (): void {
    $this->get('/')
        ->assertSuccessful()
        ->assertDontSee('aria-label="Open notifications"', false);
});
