<?php

namespace App\Filament\Workforce\Resources\Downtimes\Schemas;

use App\Enums\RevenueTypes;
use App\Models\Campaign;
use App\Models\DowntimeReason;
use App\Models\Employee;
use Filament\Schemas\Schema;
use App\Services\ModelListService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;

class DowntimeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->default(now())
                    ->required(),
                Select::make('employee_id')
                    ->options(ModelListService::get(model: Employee::query(), value_field: 'full_name'))
                    ->searchable()
                    ->required(),
                Select::make('campaign_id')
                    ->options(ModelListService::get(model: Campaign::query()->where('revenue_type', RevenueTypes::Downtime)))
                    ->searchable()
                    ->required(),
                Select::make('downtime_reason_id')
                    ->options(ModelListService::get(model: DowntimeReason::query()))
                    ->searchable()
                    ->required(),
                TextInput::make('time')
                    ->required()
                    ->numeric(),
            ]);
    }
}
