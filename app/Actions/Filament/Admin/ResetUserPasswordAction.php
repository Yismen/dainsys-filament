<?php

namespace App\Actions\Filament\Admin;

use App\Models\User;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class ResetUserPasswordAction
{
    public static function make(string $name = 'reset_user_password'): Action
    {
        return Action::make($name)
            ->label(__('filament.reset_password'))
            ->icon('heroicon-o-key')
            ->requiresConfirmation()
            ->visible(fn (User $record): bool => filled($record->email))
            ->authorize(fn (): bool => Auth::user()?->can('update user') ?? false)
            ->action(function (User $record, Component $livewire): void {
                $url = self::generateSignedResetPasswordUrl($record);

                $livewire->js('window.open('.json_encode($url).", '_blank')");
            });
    }

    public static function generateSignedResetPasswordUrl(User $user): string
    {
        return URL::temporarySignedRoute(
            'password.reset.admin',
            now()->addMinutes(120),
            [
                'user' => $user,
            ],
        );
    }
}
