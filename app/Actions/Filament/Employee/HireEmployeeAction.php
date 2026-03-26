<?php

namespace App\Actions\Filament\Employee;

use App\Models\Employee;
use App\Models\Position;
use App\Models\Project;
use App\Models\Site;
use App\Models\Supervisor;
use App\Schemas\Filament\HumanResource\HireEmployeeSchema;
use App\Services\ModelListService;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Support\Colors\Color;
use Illuminate\Support\Arr;

class HireEmployeeAction
{
    public static function make(string $name = 'hire'): Action
    {
        return
            Action::make($name)
                ->visible(fn (Employee $record) => $record->canBeHired())
                ->color(Color::Green)
                ->schema([
                    Grid::make()
                        ->columns(2)
                        ->schema(HireEmployeeSchema::make(isBeingHired: true)),
                // ->schema([
                //     Grid::make()
                //         ->columns(2)
                //         ->schema([
                //             DateTimePicker::make('date')
                //                 ->label(label: __('Date'))
                //                 ->required()
                //                 ->default(now()),
                //             Select::make('site_id')
                //                 ->label(__('Site'))
                //                 ->options(ModelListService::get(Site::class))
                //                 ->searchable()
                //                 ->required(),
                //             Select::make('project_id')
                //                 ->label(label: __('Project'))
                //                 ->options(ModelListService::get(Project::class))
                //                 ->searchable()
                //                 ->required(),
                //             Select::make(name: 'position_id')
                //                 ->label(__('Position'))
                //                 ->options(ModelListService::get(Position::class))
                //                 ->searchable()
                //                 ->required(),
                //             Select::make('supervisor_id')
                //                 ->label(__('Supervisor'))
                //                 ->options(ModelListService::get(Supervisor::class))
                //                 ->searchable()
                //                 ->required(),

                //         ]),
                ])->action(function (Employee $record, $data): void {
                    $data['date'] = $data['date'] ?? $data['hired_at']; // On the form, we have 'hired_at' field, but for the hires relationship, we need to save it as 'date'
                    $data = Arr::except($data, ['internal_id', 'hired_at']); // We don't want to save 'internal_id' and 'hired_at' in the hires table, as they are saved in the employees table

                    $record->hires()->create($data);

                    $record->refresh();

                    Notification::make()
                        ->success()
                        ->body("Employee {$record->full_name} has been hired")
                        ->send();
                });
    }
}
