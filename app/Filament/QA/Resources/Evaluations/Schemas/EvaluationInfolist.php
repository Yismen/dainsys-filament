<?php

namespace App\Filament\QA\Resources\Evaluations\Schemas;

use App\Models\Evaluation;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EvaluationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->copyable(),
                TextEntry::make('evaluation_date')
                    ->date(),
                TextEntry::make('employee.full_name')
                    ->label('Employee'),
                TextEntry::make('supervisor.name')
                    ->label('Supervisor')
                    ->placeholder('-'),
                TextEntry::make('evaluator.name')
                    ->label('Evaluator')
                    ->placeholder('-'),
                TextEntry::make('qaForm.name')
                    ->label('QA Form')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('threshold_percentage')
                    ->label('Threshold %'),
                TextEntry::make('points_possible'),
                TextEntry::make('points_achieved'),
                TextEntry::make('success_percentage')
                    ->label('Success %'),
                TextEntry::make('comments')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('employee_decision_comment')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('manager_resolution_comment')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('accepted_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('disputed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('resolved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Evaluation $record): bool => $record->trashed()),
            ]);
    }
}
