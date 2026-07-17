<?php

namespace App\Filament\Workforce\Resources\Campaigns\Schemas;

use App\Models\Campaign;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CampaignInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('filament.id'))
                    ->columnSpanFull(),
                TextEntry::make('name'),
                TextEntry::make('project.name')
                    ->label(__('filament.project')),
                TextEntry::make('source.name')
                    ->label(__('filament.source')),
                TextEntry::make('revenue_type')
                    ->badge(),
                TextEntry::make('sph_goal')
                    ->numeric(),
                TextEntry::make('revenue_rate')
                    ->numeric(),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Campaign $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
