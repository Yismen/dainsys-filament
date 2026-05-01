<?php

namespace App\Actions\Filament\Admin;

use App\Models\User;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class ResetUserPasswordAction
{
    public static function make(string $name = 'reset_user_password'): Action
    {
        return Action::make($name)
            ->label(__('Reset Password'))
            ->icon('heroicon-o-key')
            ->requiresConfirmation()
            ->visible(fn (User $record): bool => filled($record->email))
            ->authorize(fn (): bool => Auth::user()?->can('update user') ?? false)
            ->action(fn (User $record) => redirect(self::generateSignedResetPasswordUrl($record)));
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
