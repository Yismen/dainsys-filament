<?php

namespace App\Actions\Filament\Employee;

use App\Enums\TerminationTypes;
use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Support\Colors\Color;

class TerminateEmployeeAction
{
    public static function make(string $name = 'terminate'): Action
    {
        return
            Action::make($name)
                ->visible(fn (Employee $record) => $record->canBeTerminated())
                ->color(Color::Red)
                ->schema([
                    Grid::make()
                        ->columns(3)
                        ->schema([
                            DateTimePicker::make('date')
                                ->label(__('filament.date'))
                                ->required()
                                ->default(now()),
                            Select::make('termination_type')
                                ->label(__('filament.termination_type'))
                                ->options(TerminationTypes::toArray())
                                ->searchable()
                                ->required(),
                            Toggle::make('is_rehireable')
                                ->label(__('filament.can_be_rehired'))
                                ->inline(false)
                                ->default(true),
                            Textarea::make('comment')
                                ->label(__('filament.comment'))
                                ->columnSpanFull()
                                ->required()
                                ->minLength(5),
                        ]),

                ])->action(function (Employee $record, $data): void {
                    $record->terminations()->create($data);

                    $record->refresh();

                    Notification::make()
                        ->danger()
                        ->body(__('filament.employee_terminated_body', ['name' => $record->full_name]))
                        ->send();
                });
    }
}
