<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';

    public ?string $statusMessage = null;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendResetLink(): void
    {
        $this->statusMessage = null;

        $credentials = $this->validate();

        $status = Password::sendResetLink($credentials);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }

        $this->statusMessage = __($status);
    }

    public function render(): View
    {
        return view('livewire.auth.forgot-password');
    }
}
