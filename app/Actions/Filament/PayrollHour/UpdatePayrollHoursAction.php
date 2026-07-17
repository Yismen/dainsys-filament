<?php

namespace App\Actions\Filament\PayrollHour;

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
            ->modalHeading(__('filament.update_payroll_hours'))
            ->modalDescription(__('filament.update_payroll_hours_description'))
            ->successNotificationTitle(__('filament.payroll_hours_queued'))
            ->schema([
                Select::make('week_ending_at')
                    ->label(__('filament.week_ending_at'))
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
