<?php

namespace App\Filament\Recruitment\Resources\JobOpenings\Schemas;

use Filament\Infolists\Components\TextEntry;

class JobOpeningInfolist
{
    public static function schema(): array
    {
        return [
            TextEntry::make('title')
                ->label(__('filament.title')),
            TextEntry::make('status')
                ->label(__('filament.status'))
                ->badge(),
            TextEntry::make('position.name')
                ->label(__('filament.position'))
                ->placeholder('-'),
            TextEntry::make('department.name')
                ->label(__('filament.department'))
                ->placeholder('-'),
            TextEntry::make('site.name')
                ->label(__('filament.site'))
                ->placeholder('-'),
            TextEntry::make('openings_count')
                ->label(__('filament.openings_count')),
            TextEntry::make('opened_at')
                ->label(__('filament.opened_at'))
                ->date()
                ->placeholder('-'),
            TextEntry::make('closed_at')
                ->label(__('filament.closed_at'))
                ->date()
                ->placeholder('-'),
            TextEntry::make('description')
                ->label(__('filament.description'))
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
