<?php

namespace App\Filament\Recruitment\Resources\ApplicationStageEvents\Schemas;

use App\Enums\StageOutcome;
use App\Models\Application;
use App\Models\RecruitmentStage;
use App\Services\ModelListService;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class ApplicationStageEventForm
{
    public static function schema(): array
    {
        return [
            Select::make('application_id')
                ->label(__('filament.application'))
                ->options(ModelListService::make(model: Application::query(), value_field: 'id'))
                ->searchable()
                ->required(),
            Select::make('recruitment_stage_id')
                ->label(__('filament.recruitment_stage'))
                ->options(ModelListService::make(model: RecruitmentStage::query()->orderBy('order'), value_field: 'name'))
                ->searchable()
                ->required(),
            Select::make('outcome')
                ->label(__('filament.outcome'))
                ->options(StageOutcome::toArray())
                ->default(StageOutcome::Pending)
                ->required(),
            DateTimePicker::make('scheduled_at')
                ->label(__('filament.scheduled_at'))
                ->nullable(),
            DateTimePicker::make('completed_at')
                ->label(__('filament.completed_at'))
                ->nullable(),
            Textarea::make('notes')
                ->label(__('filament.notes'))
                ->nullable()
                ->columnSpanFull(),
        ];
    }
}
