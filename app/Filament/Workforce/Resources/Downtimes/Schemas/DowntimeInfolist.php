<?php

namespace App\Filament\Workforce\Resources\Downtimes\Schemas;

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
                    ->label(__('filament.id')),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('employee.full_name')
                    ->label(__('filament.employee')),
                TextEntry::make('campaign.name')
                    ->label(__('filament.campaign')),
                TextEntry::make('downtimeReason.name')
                    ->label(__('filament.downtime_reason')),
                TextEntry::make('total_time')
                    ->numeric(),
                TextEntry::make('requester.name')
                    ->label(__('filament.requester'))
                    ->placeholder('-'),
                TextEntry::make('aprover.name')
                    ->label(__('filament.approver'))
                    ->placeholder('-'),
                TextEntry::make('comments')
                    ->label(__('filament.comments'))
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
