<?php

namespace App\Filament\OperationsDirector\Resources\Incentives\Schemas;

use App\Models\Incentive;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class IncentiveInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('id')
                    ->label(__('filament.id'))
                    ->columnSpanFull(),
                TextEntry::make('payable_date')
                    ->date(),
                TextEntry::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->placeholder('-'),
                TextEntry::make('project.name')
                    ->label(__('filament.project'))
                    ->placeholder('-'),
                TextEntry::make('total_production_hours')
                    ->numeric(),
                TextEntry::make('total_sales')
                    ->numeric(),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('notes')
                    ->columnSpanFull()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Incentive $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
