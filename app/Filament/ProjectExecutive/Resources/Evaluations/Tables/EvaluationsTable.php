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
                    ->label(__('filament.id'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('record_number')
                    ->label(__('filament.record_number'))
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->copyable(),
                TextColumn::make('evaluation_date')
                    ->label(__('filament.record_date'))
                    ->date()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('evaluator.name')
                    ->label(__('filament.evaluator'))
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('qaForm.name')
                    ->label(__('filament.qa_form'))
                    ->sortable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('threshold_percentage')
                    ->label(__('filament.threshold'))
                    ->wrap()
                    ->sortable(),
                TextColumn::make('success_percentage')
                    ->label(__('filament.score'))
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
