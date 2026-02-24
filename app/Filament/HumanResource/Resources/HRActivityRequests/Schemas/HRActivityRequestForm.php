<?php

namespace App\Filament\HumanResource\Resources\HRActivityRequests\Schemas;

use App\Enums\HRActivityTypes;
use App\Models\Employee;
use App\Models\Supervisor;
use App\Services\ModelListService;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HRActivityRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->options(ModelListService::make(
                        model: Employee::query()->active(),
                        value_field: 'full_name',
                    ))
                    ->searchable()
                    ->required(),
                Select::make('supervisor_id')
                    ->options(ModelListService::make(Supervisor::query()))
                    ->searchable()
                    ->required(),
                Select::make('activity_type')
                    ->options(HRActivityTypes::class)
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                DateTimePicker::make('requested_at')
                    ->required(),
            ]);
    }
}
