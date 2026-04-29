<?php

namespace App\Filament\Recruitment\Resources\Applications\Schemas;

use App\Enums\ApplicationStatuses;
use App\Models\Applicant;
use App\Models\JobOpening;
use App\Services\ModelListService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class ApplicationForm
{
    public static function schema(): array
    {
        return [
            Select::make('applicant_id')
                ->label(__('filament.applicant'))
                ->options(ModelListService::make(model: Applicant::query(), value_field: 'name'))
                ->searchable()
                ->required(),
            Select::make('job_opening_id')
                ->label(__('filament.job_opening'))
                ->options(ModelListService::make(model: JobOpening::query(), value_field: 'title'))
                ->searchable()
                ->required(),
            Select::make('status')
                ->label(__('filament.status'))
                ->options(ApplicationStatuses::toArray())
                ->default(ApplicationStatuses::Applied)
                ->required(),
            DatePicker::make('applied_at')
                ->label(__('filament.applied_at'))
                ->default(now())
                ->nullable(),
            Textarea::make('notes')
                ->label(__('filament.notes'))
                ->nullable()
                ->columnSpanFull(),
        ];
    }
}
