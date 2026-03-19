<?php

namespace App\Actions\Filament;

use App\Jobs\RefreshPayrollHoursJob;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UpdatePayrollHoursAction
{
    public static function make(string $name = 'update_payroll_hours'): Action
    {
        return Action::make($name)
            ->button()
            ->icon(Heroicon::OutlinedCircleStack)
            ->size('sm')
            ->color(Color::Cyan)
            ->modalHeading('Update Payroll Hours')
            ->modalDescription('This will recalculate payroll hours for whole week based on production records. You can select a specific date to update hours for that week, or leave it as today\'s date to update the current week.')
            ->successNotificationTitle('Payroll hours have been queued and will be updated in the background! You will receive a notification when the process is complete.')
            ->schema([
                Select::make('week_ending_at')
                    ->label('Week Ending At')
                    ->required()
                    ->options(Cache::rememberForever('payroll_hours_week_ending_dates', function (): array {
                        $weeks = [];
                        $currentDate = Carbon::now();

                        for ($i = 0; $i < 25; $i++) {
                            $weekEnding = $currentDate->copy()->endOfWeek();
                            $weeks[$weekEnding->toDateString()] = $weekEnding->toFormattedDateString();
                            $currentDate->subWeek();
                        }

                        return $weeks;
                    })),
            ])
            ->action(function (array $data): void {
                // Refresh payroll hours for this employee/date when updated
                RefreshPayrollHoursJob::dispatch(
                    date: Carbon::parse($data['week_ending_at'])->toDateString(),
                    userToNotify: Auth::user()
                );
            });
    }
}
