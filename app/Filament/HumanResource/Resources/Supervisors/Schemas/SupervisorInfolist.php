<?php

namespace App\Filament\HumanResource\Resources\Supervisors\Schemas;

use App\Models\Supervisor;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SupervisorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('filament.id')),
                TextEntry::make('user.name')
                    ->label(__('filament.user')),
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->boolean(),
                RepeatableEntry::make('hiredEmployees')
                    ->label(__('filament.hired_employees'))
                    ->grid(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('full_name'),
                    ]),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Supervisor $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
