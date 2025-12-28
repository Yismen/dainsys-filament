<?php

namespace App\Filament\HumanResource\Resources\Departments\Schemas;

use App\Models\Department;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Department $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
