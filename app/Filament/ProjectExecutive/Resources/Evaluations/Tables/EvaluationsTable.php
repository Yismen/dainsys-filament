<?php

namespace App\Filament\ProjectExecutive\Resources\Evaluations\Tables;

use App\Enums\EvaluationStatuses;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EvaluationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('evaluation_date', 'desc')
            ->columns([
                TextColumn::make('evaluation_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('evaluator.name')
                    ->label('Evaluator')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('qaForm.name')
                    ->label('QA Form')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('success_percentage')
                    ->label('Score %')
                    ->sortable(),
                TextColumn::make('threshold_percentage')
                    ->label('Threshold %')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(EvaluationStatuses::class),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalWidth('4xl'),
            ]);
    }
}
