<?php

namespace App\Filament\Recruitment\Resources\RecruitmentStages\Schemas;

use Filament\Infolists\Components\TextEntry;

class RecruitmentStageInfolist
{
    public static function schema(): array
    {
        return [
            TextEntry::make('name')
                ->label(__('filament.name')),
            TextEntry::make('order')
                ->label(__('filament.order')),
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
