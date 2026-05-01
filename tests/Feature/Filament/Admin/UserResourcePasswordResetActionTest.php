<?php

use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

it('allows authorized admin users to mount reset user password action', function (): void {
    $targetUser = User::factory()->create([
        'email' => 'target-user@example.com',
    ]);

    actingAs($this->createUserWithPermissionsToActions(['view-any', 'update'], 'User'));

    livewire(ListUsers::class)
        ->mountTableAction('reset_user_password', $targetUser->getKey())
        ->assertOk();
});

it('generates reset link only when action is executed', function (): void {
    $targetUser = User::factory()->create([
        'email' => 'target-user@example.com',
    ]);

    actingAs($this->createUserWithPermissionsToActions(['view-any', 'update'], 'User'));

    livewire(ListUsers::class)
        ->callTableAction('reset_user_password', $targetUser->getKey())
        ->assertRedirect();
});
