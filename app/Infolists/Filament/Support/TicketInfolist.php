<?php

namespace App\Infolists\Filament\Support;

use App\Enums\TicketStatuses;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;

class TicketInfolist
{
    public static function make(): array
    {
        return [
            TextEntry::make('reference')
                ->columnSpanFull(),
            TextEntry::make('status')
                ->badge()
                ->color(fn ($state) => $state->color() ?? TicketStatuses::from($state)->color()),
            TextEntry::make('priority')
                ->badge(),
            TextEntry::make('owner.name')
                ->label(__('Created by')),
            TextEntry::make('created_at')
                ->label(__('Created at'))
                ->dateTime(),
            TextEntry::make('subject')
                ->label('Subject'),
            TextEntry::make('description')
                ->label('Description'),
            TextEntry::make('agent.name')
                ->label('Assigned to'),
            TextEntry::make('assigned_at')
                ->dateTime(),
            TextEntry::make('expected_at')
                ->dateTime(),
            TextEntry::make('completed_at')
                ->wrap()
                ->dateTime(),
            ImageEntry::make('images')
                ->disk('public')
                ->openUrlInNewTab()
                ->url(url: fn (string $state) => \asset('storage/'.$state), shouldOpenInNewTab: true)
                ->circular()
                ->stacked()
                // ->limit(3)
                ->limitedRemainingText(),
        ];
    }
}
