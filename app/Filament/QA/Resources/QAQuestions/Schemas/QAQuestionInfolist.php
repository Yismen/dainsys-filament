<?php

namespace App\Filament\QA\Resources\QAQuestions\Schemas;

use App\Models\QAQuestion;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QAQuestionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID')
                    ->copyable(),
                TextEntry::make('form.name')
                    ->label('QA Form'),
                TextEntry::make('display_order'),
                TextEntry::make('max_points'),
                TextEntry::make('is_active')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive'),
                TextEntry::make('author.name')
                    ->label('Author')
                    ->placeholder('-'),
                TextEntry::make('text')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (QAQuestion $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime(),
            ]);
    }
}
