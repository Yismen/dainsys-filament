<?php

namespace App\Filament\HumanResource\Resources\Absences\Schemas;

use App\Models\Absence;
use Filament\Infolists\Components\TextEntry;

class AbsenceInfolist
{
    public static function schema(): array
    {
        return [
            TextEntry::make('employee.full_name')
                ->label(__('filament.employee')),
            TextEntry::make('date')
                ->date(),
            TextEntry::make('status')
                ->badge(),
            TextEntry::make('type')
                ->badge()
                ->placeholder('-'),
            TextEntry::make('comment')
                ->placeholder('-')
                ->columnSpanFull(),
            TextEntry::make('creator.name')
                ->label(__('filament.reported_by')),
            TextEntry::make('created_at')
                ->dateTime()
                ->placeholder('-'),
            TextEntry::make('updated_at')
                ->dateTime()
                ->placeholder('-'),
            TextEntry::make('deleted_at')
                ->dateTime()
                ->visible(fn (Absence $record): bool => $record->trashed()),
        ];
    }
}
