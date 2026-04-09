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
                    ->label(__('ID'))
                    ->columnSpanFull(),
                TextEntry::make('name')
                    ->label(__('Name')),
                TextEntry::make('project.name')
                    ->label(__('Project'))
                    ->placeholder('-'),
                TextEntry::make('source.name')
                    ->label(__('Source'))
                    ->placeholder('-'),
                TextEntry::make('invoiceAgent.name')
                    ->label(__('Agent'))
                    ->placeholder(__('Unassigned')),
                TextEntry::make('revenue_type')
                    ->label(__('Revenue type'))
                    ->badge(),
                TextEntry::make('sph_goal')
                    ->label(__('SPH goal'))
                    ->numeric(),
                TextEntry::make('revenue_rate')
                    ->label(__('Revenue rate'))
                    ->numeric(),
                TextEntry::make('description')
                    ->label(__('Description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->label(__('Deleted at'))
                    ->dateTime()
                    ->visible(fn (Campaign $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
