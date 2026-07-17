<?php

namespace App\Filament\HumanResource\Resources\HRActivityRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HRActivityRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament.request_information'))
                    ->schema([
                        TextEntry::make('id')
                            ->label(__('filament.id')),
                        TextEntry::make('employee.full_name')
                            ->label(__('filament.employee')),
                        TextEntry::make('supervisor.name')
                            ->label(__('filament.supervisor')),
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
                Section::make(__('filament.details'))
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
