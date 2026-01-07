<?php

namespace App\Filament\Actions;

use App\Enums\TerminationTypes;
use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
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
                    DateTimePicker::make('date')
                        ->label(label: __('Date'))
                        ->required()
                        ->default(now()),
                    Select::make('termination_type')
                        ->label(__('Termination Type'))
                        ->options(TerminationTypes::toArray())
                        ->searchable()
                        ->required(),
                    Toggle::make('is_rehireable')
                        ->label(__('Can Be Re-hired?'))
                        ->default(true),
                    Textarea::make('comment')
                        ->label(__('Comment'))
                        ->required()
                        ->minLength(5),

                ])->action(function (Employee $record, $data) {
                    $record->terminations()->create($data);

                    Notification::make()
                        ->danger()
                        ->body("Employee {$record->full_name} has been terminated")
                        ->send();
                });
    }
}
