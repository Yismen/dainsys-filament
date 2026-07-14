<?php

namespace App\Actions\Filament\Workforce;

use App\Models\Payroll;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class DeletePayrollsByPayableDateAction
{
    public static function make(string $name = 'deleteByPayableDate'): Action
    {
        return Action::make($name)
            ->label('Delete by payable date')
            ->icon(Heroicon::Trash)
            ->color(Color::Red)
            ->form([
                Select::make('payable_date')
                    ->label('Payable date')
                    ->required()
                    ->options(fn (): array => Payroll::query()
                        ->whereDate('payable_date', '>=', now())
                        ->distinct()
                        ->orderBy('payable_date')
                        ->pluck('payable_date', 'payable_date')
                        ->toArray()
                    ),
            ])
            ->action(function (array $data): void {
                $payableDate = $data['payable_date'];

                $count = Payroll::query()
                    ->whereDate('payable_date', $payableDate)
                    ->count();

                Payroll::query()
                    ->whereDate('payable_date', $payableDate)
                    ->delete();

                Notification::make()
                    ->success()
                    ->title('Payrolls deleted')
                    ->body("{$count} payroll(s) with payable date {$payableDate} have been deleted.")
                    ->send();
            });
    }
}
