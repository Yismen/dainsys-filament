<?php

namespace App\Filament\Supervisor\Resources\Downtimes\Schemas;

use App\Models\Downtime;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DowntimeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('employee.full_name')
                    ->label('Employee'),
                TextEntry::make('campaign.name')
                    ->label('Campaign'),
                TextEntry::make('downtimeReason.name')
                    ->label('Downtime reason'),
                TextEntry::make('total_time')
                    ->numeric(),
                TextEntry::make('requester.name')
                    ->label('Requester')
                    ->placeholder('-'),
                TextEntry::make('aprover.name')
                    ->label('Aprover')
                    ->placeholder('-'),
                TextEntry::make('comments')
                    ->label('Comments')
                    ->columnSpanFull()
                    ->formatStateUsing(function (Downtime $record): string {
                        return $record->comments
                            ->sortBy('created_at')
                            ->map(fn ($comment) => sprintf(
                                '%s — %s',
                                optional($comment->created_at)->format('Y-m-d H:i'),
                                $comment->text
                            ))
                            ->implode('<br>');
                    })
                    ->html()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Downtime $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
