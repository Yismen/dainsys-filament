<?php

namespace App\Actions\Filament\Workforce;

use App\Models\Incentive;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class DeleteIncentivesByPayableDateAction
{
    public static function make(string $name = 'deleteByPayableDate'): Action
    {
        return Action::make($name)
            ->label(__('filament.delete_by_payable_date'))
            ->icon(Heroicon::Trash)
            ->color(Color::Red)
            ->form([
                Select::make('payable_date')
                    ->label(__('filament.payable_date'))
                    ->required()
                    ->options(fn (): array => Incentive::query()
                        ->whereDate('payable_date', '>=', now())
                        ->distinct()
                        ->orderBy('payable_date')
                        ->pluck('payable_date', 'payable_date')
                        ->toArray()
                    ),
            ])
            ->action(function (array $data): void {
                $payableDate = $data['payable_date'];

                $count = Incentive::query()
                    ->whereDate('payable_date', $payableDate)
                    ->count();

                Incentive::query()
                    ->whereDate('payable_date', $payableDate)
                    ->delete();

                Notification::make()
                    ->success()
                    ->title(__('filament.incentives_deleted'))
                    ->body(__('filament.incentives_deleted_body', ['count' => $count, 'payableDate' => $payableDate]))
                    ->send();
            });
    }
}
