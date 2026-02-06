<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ResetPassword extends Component
{
    public string $token;

    public string $email = '';

    public bool $emailLocked = false;

    public ?string $lockedEmail = null;

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(string $token, ?string $email = null): void
    {
        $this->token = $token;
        if ($email !== null && $email !== '') {
            $this->emailLocked = true;
            $this->lockedEmail = $email;
            $this->email = $email;

            return;
        }

        $this->emailLocked = false;
        $this->lockedEmail = null;
        $this->email = '';
    }

    protected function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'confirmed', PasswordRule::defaults()],
        ];
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resetPassword(): void
    {
        if ($this->emailLocked && $this->lockedEmail !== null) {
            $this->email = $this->lockedEmail;
        }

        $credentials = $this->validate();

        $status = Password::reset($credentials, function ($user) use ($credentials): void {
            $user->forceFill([
                'password' => Hash::make($credentials['password']),
                'remember_token' => \Illuminate\Support\Str::random(60),
            ])->save();
        });

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }

        $this->redirectRoute('login');
    }

    public function render(): View
    {
        return view('livewire.auth.reset-password');
    }
}
