<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
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
