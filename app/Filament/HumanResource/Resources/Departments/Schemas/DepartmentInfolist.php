<?php

namespace App\Filament\HumanResource\Resources\Departments\Schemas;

use App\Models\Department;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('filament.id')),
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                RepeatableEntry::make('hiredEmployees')
                    ->label(__('Hired Employees'))
                    ->grid(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('full_name'),
                    ]),
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
