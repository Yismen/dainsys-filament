<?php

namespace App\Filament\Recruitment\Resources\ApplicationStageEvents\Schemas;

use Filament\Infolists\Components\TextEntry;

class ApplicationStageEventInfolist
{
    public static function schema(): array
    {
        return [
            TextEntry::make('application.applicant.name')
                ->label(__('filament.applicant')),
            TextEntry::make('recruitmentStage.name')
                ->label(__('filament.recruitment_stage')),
            TextEntry::make('outcome')
                ->label(__('filament.outcome'))
                ->badge(),
            TextEntry::make('scheduled_at')
                ->label(__('filament.scheduled_at'))
                ->dateTime()
                ->placeholder('-'),
            TextEntry::make('completed_at')
                ->label(__('filament.completed_at'))
                ->dateTime()
                ->placeholder('-'),
            TextEntry::make('notes')
                ->label(__('filament.notes'))
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('created_at')
                ->label(__('filament.created_at'))
                ->dateTime(),
            TextEntry::make('updated_at')
                ->label(__('filament.updated_at'))
                ->dateTime(),
        ];
    }
}
