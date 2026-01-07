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
                    ->label('ID'),
                TextEntry::make('employee.full_name')
                    ->label('Employee'),
                TextEntry::make('date')
                    ->dateTime(),
                TextEntry::make('site.name')
                    ->label('Site'),
                TextEntry::make('project.name')
                    ->label('Project'),
                TextEntry::make('position.name')
                    ->label('Position'),
                TextEntry::make('supervisor.name')
                    ->label('Supervisor'),
                TextEntry::make('punch')
                    ->placeholder('-'),
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
