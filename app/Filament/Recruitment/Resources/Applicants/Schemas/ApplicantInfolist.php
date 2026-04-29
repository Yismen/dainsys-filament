<?php

namespace App\Filament\Recruitment\Resources\Applicants\Schemas;

use Filament\Infolists\Components\TextEntry;

class ApplicantInfolist
{
    public static function schema(): array
    {
        return [
            TextEntry::make('name')
                ->label(__('filament.name')),
            TextEntry::make('email')
                ->label(__('filament.email')),
            TextEntry::make('phone')
                ->label(__('filament.phone'))
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
