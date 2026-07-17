<?php

namespace App\Filament\HumanResource\Resources\Hires\Schemas;

use App\Models\Hire;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HireInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('filament.id')),
                TextEntry::make('employee.full_name')
                    ->label(__('filament.employee')),
                TextEntry::make('date')
                    ->dateTime(),
                TextEntry::make('site.name')
                    ->label(__('filament.site')),
                TextEntry::make('project.name')
                    ->label(__('filament.project')),
                TextEntry::make('position.details')
                    ->label(__('filament.position')),
                TextEntry::make('supervisor.name')
                    ->label(__('filament.supervisor')),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Hire $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
