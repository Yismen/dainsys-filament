<?php

namespace App\Filament\Employee\Pages;

use App\Models\User;
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

    protected static ?int $navigationSort = 40;

    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();

        return $user && $user->force_password_change;
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('currentPassword')
                ->label(__('filament.current_password'))
                ->password()
                ->required()
                ->currentPassword(),
            TextInput::make('password')
                ->label(__('filament.new_password'))
                ->password()
                ->required()
                ->rule(Password::default())
                ->confirmed(),
            TextInput::make('passwordConfirmation')
                ->label(__('filament.confirm_password'))
                ->password()
                ->required(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament.update_password'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $this->validate([
            'currentPassword' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = Filament::auth()->user();
        $user->update([
            'password' => Hash::make($this->password),
            'password_set_at' => now(),
            'force_password_change' => false,
        ]);

        Notification::make()
            ->success()
            ->title(__('filament.password_updated'))
            ->body(__('filament.password_updated_body'))
            ->send();

        redirect()->to('/employee');
    }
}
