<?php

namespace App\Filament\Supervisor\Resources\Evaluations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EvaluationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('record_number')
                    ->label('Record Number')
                    ->copyable(),
                TextEntry::make('evaluation_date')
                    ->date(),
                TextEntry::make('employee.full_name')
                    ->label('Employee'),
                TextEntry::make('evaluator.name')
                    ->label('Evaluator'),
                TextEntry::make('qaForm.name')
                    ->label('QA Form'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('success_percentage')
                    ->label('Score %'),
                TextEntry::make('threshold_percentage')
                    ->label('Threshold %'),
                TextEntry::make('points_achieved'),
                TextEntry::make('points_possible'),
                TextEntry::make('comments')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('employee_decision_comment')
                    ->label('Employee comment')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('manager_resolution_comment')
                    ->label('Resolution comment')
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
