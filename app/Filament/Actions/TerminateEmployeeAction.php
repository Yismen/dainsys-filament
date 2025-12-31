<?php

namespace App\Filament\Actions;

use App\Models\Site;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Supervisor;
use Filament\Actions\Action;
use App\Enums\TerminationTypes;
use App\Services\ModelListService;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;

class TerminateEmployeeAction
{
    public static function make(string $name = 'terminate'): Action
    {
        return
            Action::make($name)
                ->visible(fn(Employee $record) => $record->canBeTerminated())
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
                        ->preload()
                        ->required(),
                    Toggle::make('is_rehireable')
                        ->label(__('Can Be Re-hired?'))
                        ->default(true)

                ])->action(function (Employee $record, $data) {
                    $record->terminations()->create($data);

                    Notification::make()
                        ->danger()
                        ->body("Employee {$record->full_name} has been terminated")
                        ->send();
                });
    }
}
