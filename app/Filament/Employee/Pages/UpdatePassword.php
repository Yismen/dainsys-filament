<?php

namespace App\Filament\Employee\Pages;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdatePassword extends Page implements HasForms
{
    use InteractsWithForms;

    public ?string $currentPassword = null;

    public ?string $password = null;

    public ?string $passwordConfirmation = null;

    public function mount(): void
    {
        $user = Filament::auth()->user();
        abort_unless($user && $user->force_password_change, 403);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('currentPassword')
                ->label('Current Password')
                ->password()
                ->required()
                ->currentPassword(),
            TextInput::make('password')
                ->label('New Password')
                ->password()
                ->required()
                ->rule(Password::default())
                ->confirmed(),
            TextInput::make('passwordConfirmation')
                ->label('Confirm Password')
                ->password()
                ->required(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Update Password')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $this->validate([
            'currentPassword' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Filament::auth()->user();
        $user->update([
            'password' => Hash::make($this->password),
            'password_set_at' => now(),
            'force_password_change' => false,
        ]);

        Notification::make()
            ->success()
            ->title('Password Updated')
            ->body('Your password has been successfully updated.')
            ->send();

        redirect()->to('/employee');
    }
}
