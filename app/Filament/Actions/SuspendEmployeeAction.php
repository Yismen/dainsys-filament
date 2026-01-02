<?php

namespace App\Filament\Actions;

use App\Models\Employee;
use App\Models\SuspensionType;
use App\Services\ModelListService;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Colors\Color;

class SuspendEmployeeAction
{
    public static function make(string $name = 'suspend'): Action
    {
        return
            Action::make($name)
                ->visible(fn (Employee $record) => $record->canBeSuspended())
                ->color(Color::Yellow)
                ->schema([
                    Select::make('suspension_type_id')
                        ->label(__('Suspension Type'))
                        ->options(ModelListService::get(SuspensionType::class))
                        ->searchable()
                        ->preload()
                        ->required(),
                    DateTimePicker::make('starts_at')
                        ->default(function (Employee $record) {
                            return $record->latestHire()->date;
                        })
                        ->minDate(function (Employee $record) {
                            return $record->latestHire()->date?->startOfDay();
                        })
                        ->maxDate(now()->addMonths(2)->endOfDay())
                        ->required()
                        ->live(),
                    DateTimePicker::make('ends_at')
                        ->default(now()->endOfDay())
                        ->minDate(fn (Get $get) => $get('starts_at') ?? now())
                        ->maxDate(now()->endOfDay()->addYear())
                        ->required()
                        ->live(),
                ])->action(function (Employee $record, $data) {
                    $record->suspensions()->create($data);

                    Notification::make()
                        ->warning()
                        ->body("Employee {$record->full_name} has been suspended")
                        ->send();
                });
    }
}
