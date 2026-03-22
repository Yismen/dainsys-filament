<?php

namespace App\Actions\Filament\Employee;

use App\Models\Employee;
use App\Notifications\EmployeePasswordReset;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ResetEmployeePasswordAction
{
    public static function make(string $name = 'reset_employee_password'): Action
    {
        return Action::make($name)
            ->icon('heroicon-o-key')
            ->requiresConfirmation()
            ->visible(fn (Employee $record): bool => (bool) $record->user && $record->user->password_set_at)
            ->action(function (Employee $employee): void {
                self::resetEmployeePassword($employee);
            })
            ->after(function (Employee $employee): void {
                Notification::make()
                    ->success()
                    ->title('Password Reset')
                    ->body('Password has been reset. Supervisor has been notified.')
                    ->sendToDatabase(auth()->user());

                $supervisor = $employee->supervisor;

                if ($supervisor && $supervisor->user) {
                    $supervisor->user->notify(new EmployeePasswordReset($employee));
                }
            });
    }

    private static function resetEmployeePassword(Employee $employee): void
    {
        $user = $employee->user;

        if (! $user) {
            return;
        }

        $user->update([
            'force_password_change' => true,
            'password_set_at' => null,
        ]);
    }
}
