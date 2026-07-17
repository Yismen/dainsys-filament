<?php

namespace App\Filament\OperationsDirector\Resources\Evaluations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EvaluationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('record_number')
                    ->label(__('filament.record_number'))
                    ->copyable(),
                TextEntry::make('evaluation_date')
                    ->date(),
                TextEntry::make('employee.full_name')
                    ->label(__('filament.employee')),
                TextEntry::make('evaluator.name')
                    ->label(__('filament.evaluator')),
                TextEntry::make('qaForm.name')
                    ->label(__('filament.qa_form')),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('success_percentage')
                    ->label(__('filament.score')),
                TextEntry::make('threshold_percentage')
                    ->label(__('filament.threshold')),
                TextEntry::make('points_achieved'),
                TextEntry::make('points_possible'),
                TextEntry::make('comments')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('employee_decision_comment')
                    ->label(__('filament.employee_comment'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('manager_resolution_comment')
                    ->label(__('filament.resolution_comment'))
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
            ]);
    }
}
