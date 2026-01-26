<?php

namespace App\Filament\HumanResource\Resources\HRActivityRequests\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\TextEntry;
use Filament\Schemas\Schema;

class HRActivityRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Request Information')
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID'),
                        TextEntry::make('employee.full_name')
                            ->label('Employee'),
                        TextEntry::make('supervisor.full_name')
                            ->label('Supervisor'),
                        TextEntry::make('activity_type')
                            ->badge(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('requested_at')
                            ->dateTime(),
                        TextEntry::make('completed_at')
                            ->dateTime()
                            ->placeholder('Not completed yet'),
                    ])
                    ->columns(2),
                Section::make('Details')
                    ->schema([
                        TextEntry::make('description')
                            ->placeholder('No description provided')
                            ->columnSpanFull(),
                        TextEntry::make('completion_comment')
                            ->placeholder('Not completed yet')
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->completion_comment !== null),
                    ]),
            ]);
    }
}
