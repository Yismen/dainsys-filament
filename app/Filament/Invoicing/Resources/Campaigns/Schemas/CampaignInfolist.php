<?php

namespace App\Filament\Invoicing\Resources\Campaigns\Schemas;

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
                TextEntry::make('name')
                    ->label(__('filament.name')),
                TextEntry::make('project.name')
                    ->label(__('filament.project'))
                    ->placeholder('-'),
                TextEntry::make('source.name')
                    ->label(__('filament.source'))
                    ->placeholder('-'),
                TextEntry::make('invoiceAgent.name')
                    ->label(__('filament.agent'))
                    ->placeholder(__('filament.unassigned')),
                TextEntry::make('revenue_type')
                    ->label(__('filament.revenue_type'))
                    ->badge(),
                TextEntry::make('sph_goal')
                    ->label(__('filament.sph_goal'))
                    ->numeric(),
                TextEntry::make('revenue_rate')
                    ->label(__('filament.revenue_rate'))
                    ->numeric(),
                TextEntry::make('description')
                    ->label(__('filament.description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->label(__('filament.deleted_at'))
                    ->dateTime()
                    ->visible(fn (Campaign $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
