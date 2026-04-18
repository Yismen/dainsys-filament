<?php

namespace App\Filament\OperationsDirector\Resources\Evaluations\Tables;

use App\Enums\EvaluationStatuses;
use App\Models\Employee;
use App\Models\QAForm;
use App\Services\ModelListService;
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
                TextColumn::make('record_number')
                    ->label(__('filament.record_number'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('evaluation_date')
                    ->label(__('filament.evaluation_date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label(__('filament.employee'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('evaluator.name')
                    ->label(__('filament.evaluator'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('qaForm.name')
                    ->label(__('filament.qa_form'))
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament.status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('success_percentage')
                    ->label(__('filament.score'))
                    ->sortable(),
                TextColumn::make('threshold_percentage')
                    ->label(__('filament.threshold'))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.status'))
                    ->options(EvaluationStatuses::class),
                SelectFilter::make('employee_id')
                    ->label(__('filament.employee'))
                    ->options(ModelListService::make(Employee::query(), value_field: 'full_name'))
                    ->searchable(),
                SelectFilter::make('qa_form_id')
                    ->label(__('filament.qa_form'))
                    ->options(ModelListService::make(QAForm::query(), value_field: 'name'))
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalWidth('4xl'),
            ]);
    }
}
