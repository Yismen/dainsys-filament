<?php

namespace App\Filament\QA\Resources\QAForms\Schemas;

use App\Models\QAForm;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QAFormInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID')
                    ->copyable(),
                TextEntry::make('name'),
                TextEntry::make('project.name')
                    ->label('Project'),
                TextEntry::make('passing_threshold_percentage')
                    ->label('Passing Threshold %')
                    ->badge(),
                TextEntry::make('author.name')
                    ->label('Author')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (QAForm $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
