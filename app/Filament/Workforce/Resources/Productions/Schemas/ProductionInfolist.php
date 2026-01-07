<?php

namespace App\Filament\Workforce\Resources\Productions\Schemas;

use App\Models\Production;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID')
                    ->columnSpanFull(),
                TextEntry::make('employee.full_name')
                    ->label('Employee'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('campaign.name')
                    ->label('Campaign'),
                TextEntry::make('revenue_type')
                    ->placeholder('-'),
                TextEntry::make('supervisor.name')
                    ->label('Supervisor')
                    ->placeholder('-'),
                TextEntry::make('revenue_rate')
                    ->numeric(),
                TextEntry::make('sph_goal')
                    ->numeric(),
                TextEntry::make('conversions')
                    ->numeric(),
                TextEntry::make('total_time')
                    ->numeric(),
                TextEntry::make('production_time')
                    ->numeric(),
                TextEntry::make('talk_time')
                    ->numeric(),
                TextEntry::make('billable_time')
                    ->numeric(),
                TextEntry::make('revenue')
                    ->numeric(),
                TextEntry::make('converted_to_payroll_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Production $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
