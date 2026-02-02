<?php

namespace App\Filament\Actions;

use App\Enums\RevenueTypes;
use App\Models\Campaign;
use App\Models\Downtime;
use App\Models\DowntimeReason;
use App\Models\Employee;
use App\Services\ModelListService;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Support\Colors\Color;

class CreateMultipleDowntimesAction
{
    public static function make(string $name = 'create multiple downtimes'): Action
    {
        return Action::make($name)
            ->button()
            ->label('Create Multiple')
            ->color(Color::Indigo)
            ->schema([
                Grid::make()
                    ->columns(2)
                    ->components([
                        DatePicker::make('date')
                            ->default(now())
                            ->minDate(now()->subDays(20))
                            ->maxDate(now()->endOfDay())
                            ->required(),
                        Select::make('campaign_id')
                            ->options(ModelListService::get(model: Campaign::query()->where('revenue_type', RevenueTypes::Downtime)))
                            ->searchable()
                            ->required(),
                        Select::make('downtime_reason_id')
                            ->options(ModelListService::get(model: DowntimeReason::query()))
                            ->searchable()
                            ->required(),
                        TextInput::make('total_time')
                            ->required()
                            ->minValue(0)
                            ->maxValue(13)
                            ->numeric(),
                        CheckboxList::make('employees')
                            ->label('Select Employees')
                            ->bulkToggleable()
                            ->columns(2)
                            ->columnSpanFull()
                            ->options(ModelListService::make(
                                model: Employee::query()
                                    ->whereHas('supervisor', function ($query) {
                                        $query->where('id', auth()->user()->supervisor?->id);
                                    }),
                                value_field: 'full_name'
                            ))
                            ->required(),
                    ]),
            ])
            ->action(function (array $data) {
                $newData = [
                    'date' => $data['date'],
                    'campaign_id' => $data['campaign_id'],
                    'downtime_reason_id' => $data['downtime_reason_id'],
                    'total_time' => $data['total_time'],
                ];

                foreach ($data['employees'] as $employeeId) {
                    if (
                        Downtime::query()
                            ->whereDate('date', $newData['date'])
                            ->where('employee_id', $employeeId)
                            ->where('campaign_id', $newData['campaign_id'])
                            ->exists() === false
                    ) {
                        $employee = Employee::findOrFail($employeeId);
                        $employee->downtimes()->create($newData);

                        Notification::make()
                            ->title("{$newData['total_time']} hours of downtimes created for {$employee->full_name} on date {$newData['date']}.")
                            ->success()
                            ->send();
                    }
                }

            });
    }
}
