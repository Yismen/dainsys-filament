<?php

namespace App\Filament\Actions;

use App\Models\Employee;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Services\ModelListService;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;

class HireEmployeeAction
{
    public static function make(string $name = 'hire'): Action
    {
        return
            Action::make($name)
                ->visible(fn (Employee $record) => $record->canBeHired())
                ->color(Color::Green)
                ->schema([
                    DateTimePicker::make('date')
                        ->label(label: __('Date'))
                        ->required()
                        ->default(now()),
                    Select::make('site_id')
                        ->label(__('Site'))
                        ->options(ModelListService::get(Site::class))
                        ->searchable()
                        ->required(),
                    Select::make('project_id')
                        ->label(label: __('Project'))
                        ->options(ModelListService::get(Project::class))
                        ->searchable()
                        ->required(),
                    Select::make(name: 'position_id')
                        ->label(__('Position'))
                        ->options(ModelListService::get(Position::class))
                        ->searchable()
                        ->required(),
                    Select::make('supervisor_id')
                        ->label(__('Supervisor'))
                        ->options(ModelListService::get(Supervisor::class))
                        ->searchable()
                        ->required(),

                ])->action(function (Employee $record, $data) {
                    $record->hires()->create($data);

                    Notification::make()
                        ->success()
                        ->body("Employee {$record->full_name} has been hired")
                        ->send();
                });
    }
}
