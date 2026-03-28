<?php

namespace App\Actions\Filament\Downtime;

use App\Enums\RevenueTypes;
use App\Models\Campaign;
use App\Models\DowntimeReason;
use App\Models\Employee;
use App\Services\DowntimeService;
use App\Services\ModelListService;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                                    ->whereHas('supervisor', function ($query): void {
                                        $query->where('id', auth()->user()->supervisor?->id);
                                    }),
                                value_field: 'full_name'
                            ))
                            ->required(),
                    ]),
            ])
            ->action(function (array $data): void {
                foreach ($data['employees'] as $employeeId) {
                    DowntimeService::create(
                        employeeId: $employeeId,
                        date: $data['date'],
                        campaignId: $data['campaign_id'],
                        downtimeReasonId: $data['downtime_reason_id'],
                        totalTime: $data['total_time'],
                    );
                }
            });
    }
}
