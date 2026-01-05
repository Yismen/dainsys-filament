<?php

namespace App\Filament\Actions;

use App\Models\Employee;
use Filament\Actions\Action;
use App\Models\SuspensionType;
use Filament\Support\Enums\Size;
use App\Services\ModelListService;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Utilities\Get;

class SuspendEmployeeAction
{
    public static function make(string $name = 'suspend'): Action
    {
        return
            Action::make($name)
                ->visible(fn (Employee $record) => $record->canBeSuspended())
                ->color(Color::Yellow)
                ->size(Size::Large)
                ->schema([
                    Section::make()
                        ->columns(3)
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
                            Textarea::make('comment')
                                ->required()
                                ->columnSpanFull(),
                        ])
                ])->action(function (Employee $record, $data) {
                    $record->suspensions()->create($data);

                    Notification::make()
                        ->warning()
                        ->body("Employee {$record->full_name} has been suspended")
                        ->send();
                });
    }
}
