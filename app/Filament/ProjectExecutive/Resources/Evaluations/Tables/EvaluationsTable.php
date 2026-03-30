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
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('record_number')
                    ->label('Record #')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->copyable(),
                TextColumn::make('evaluation_date')
                    ->label('Record Date')
                    ->date()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('evaluator.name')
                    ->label('Evaluator')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('qaForm.name')
                    ->label('QA Form')
                    ->sortable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('threshold_percentage')
                    ->label('Threshold %')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('success_percentage')
                    ->label('Score %')
                    ->wrap()
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
