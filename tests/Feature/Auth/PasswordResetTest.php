<?php

use App\Actions\Filament\Admin\ResetUserPasswordAction;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

it('renders the forgot password page', function (): void {
    $this->get(route('password.request'))
        ->assertOk()
        ->assertSeeLivewire(ForgotPassword::class);
});

it('sends a password reset link', function (): void {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(ForgotPassword::class)
        ->set('email', $user->email)
        ->call('sendResetLink')
        ->assertHasNoErrors();

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

it('renders the reset password page', function (): void {
    $this->get(route('password.reset', ['token' => 'token', 'email' => 'jane@example.com']))
        ->assertOk()
        ->assertSeeLivewire(ResetPassword::class);
});

it('prefills the reset email from the query string', function (): void {
    Livewire::test(ResetPassword::class, ['token' => 'token', 'email' => 'jane@example.com'])
        ->assertSet('email', 'jane@example.com')
        ->assertSet('emailLocked', true)
        ->assertSet('lockedEmail', 'jane@example.com');
});

it('resets the password with a valid token', function (): void {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    Livewire::test(ResetPassword::class, ['token' => $token, 'email' => $user->email])
        ->set('email', $user->email)
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('resetPassword')
        ->assertRedirect(route('login'));
});

it('renders admin signed reset password page for authenticated users', function (): void {
    $admin = User::factory()->create();
    $user = User::factory()->create();

    $url = URL::temporarySignedRoute('password.reset.admin', now()->addMinutes(120), ['user' => $user]);

    $this->actingAs($admin)
        ->get($url)
        ->assertOk()
        ->assertSeeLivewire(ResetPassword::class);
});

it('rejects expired admin signed reset password links', function (): void {
    $user = User::factory()->create();

    $url = URL::temporarySignedRoute('password.reset.admin', now()->subMinute(), ['user' => $user]);

    $this->get($url)
        ->assertForbidden();
});

it('generates temporary signed admin reset password links from action', function (): void {
    $user = User::factory()->create();

    $url = ResetUserPasswordAction::generateSignedResetPasswordUrl($user);

    expect($url)->toContain('/admin/users/'.$user->getKey().'/reset-password');
    expect($url)->toContain('signature=');

    $this->get($url)
        ->assertOk()
        ->assertSeeLivewire(ResetPassword::class);
});
