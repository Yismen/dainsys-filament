<?php

namespace App\Filament\Recruitment\Resources\Applications\Schemas;

use Filament\Infolists\Components\TextEntry;

class ApplicationInfolist
{
    public static function schema(): array
    {
        return [
            TextEntry::make('applicant.name')
                ->label(__('filament.applicant')),
            TextEntry::make('jobOpening.title')
                ->label(__('filament.job_opening')),
            TextEntry::make('status')
                ->label(__('filament.status'))
                ->badge(),
            TextEntry::make('applied_at')
                ->label(__('filament.applied_at'))
                ->date()
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
