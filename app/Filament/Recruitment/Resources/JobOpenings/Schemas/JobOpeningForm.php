<?php

namespace App\Filament\Recruitment\Resources\JobOpenings\Schemas;

use App\Enums\JobOpeningStatuses;
use App\Models\Department;
use App\Models\Position;
use App\Models\Site;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class JobOpeningForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('title')
                ->label(__('filament.title'))
                ->required()
                ->maxLength(255),
            Select::make('status')
                ->label(__('filament.status'))
                ->options(JobOpeningStatuses::toArray())
                ->default(JobOpeningStatuses::Open)
                ->required(),
            Select::make('position_id')
                ->label(__('filament.position'))
                ->options(ModelListService::make(model: Position::query(), value_field: 'name'))
                ->searchable()
                ->nullable(),
            Select::make('department_id')
                ->label(__('filament.department'))
                ->options(ModelListService::make(model: Department::query(), value_field: 'name'))
                ->searchable()
                ->nullable(),
            Select::make('site_id')
                ->label(__('filament.site'))
                ->options(ModelListService::make(model: Site::query(), value_field: 'name'))
                ->searchable()
                ->nullable(),
            TextInput::make('openings_count')
                ->label(__('filament.openings_count'))
                ->numeric()
                ->default(1)
                ->required(),
            DatePicker::make('opened_at')
                ->label(__('filament.opened_at'))
                ->nullable(),
            DatePicker::make('closed_at')
                ->label(__('filament.closed_at'))
                ->nullable(),
            Textarea::make('description')
                ->label(__('filament.description'))
                ->nullable()
                ->columnSpanFull(),
        ];
    }
}
