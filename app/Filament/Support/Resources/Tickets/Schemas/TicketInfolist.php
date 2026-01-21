<?php

namespace App\Filament\Support\Resources\Tickets\Schemas;

use App\Enums\TicketStatuses;
use App\Models\Ticket;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class TicketInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID')
                    ->columnSpanFull(),
                TextEntry::make('reference')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn ($state) => $state->color() ?? TicketStatuses::from($state)->color()),
                TextEntry::make('subject'),
                TextEntry::make('owner.name'),
                TextEntry::make('priority')
                    ->badge(),
                TextEntry::make('description')
                    ->html()
                    ->columnSpanFull(),
                TextEntry::make('agent.name')
                    ->label(__('Assigned to'))
                    ->placeholder('-'),
                TextEntry::make('assigned_at')
                    ->dateTime()
                    ->formatStateUsing(fn (Carbon $state) => $state->diffForHumans())
                    ->placeholder('-'),
                ImageEntry::make('images')
                    ->disk('public')
                    // ->imageWidth(200)
                    ->circular()
                    ->stacked()
                    ->ring(2)
                    ->overlap(2)
                    ->imageSize(200)
                    ->columnSpanFull(),
                TextEntry::make('completed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('expected_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Ticket $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
