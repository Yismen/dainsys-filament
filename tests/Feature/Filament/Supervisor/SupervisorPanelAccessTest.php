<?php

use App\Models\Supervisor;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('supervisor'),
    );
});

it('requires authentication to access the supervisor dashboard', function (): void {
    $response = get(route('filament.supervisor.pages.dashboard'));

    $response->assertRedirect(route('filament.supervisor.auth.login'));
});

it('forbids users without supervisor records', function (): void {
    actingAs(User::factory()->create());

    $response = get(route('filament.supervisor.pages.dashboard'));

    $response->assertForbidden();
});

it('forbids users with inactive supervisor records', function (): void {
    $user = User::factory()->create();
    Supervisor::factory()->create([
        'user_id' => $user->id,
        'is_active' => false,
    ]);

    actingAs($user);

    $response = get(route('filament.supervisor.pages.dashboard'));

    $response->assertForbidden();
});

it('allows users with active supervisor records', function (): void {
    $user = User::factory()->create();
    Supervisor::factory()->create([
        'user_id' => $user->id,
        'is_active' => true,
    ]);

    actingAs($user);

    $response = get(route('filament.supervisor.pages.dashboard'));

    $response->assertOk();
});
