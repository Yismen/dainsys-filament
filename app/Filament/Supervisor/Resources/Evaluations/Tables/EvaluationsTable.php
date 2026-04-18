<?php

namespace App\Filament\Supervisor\Resources\Evaluations\Tables;

use App\Enums\EvaluationStatuses;
use App\Models\Evaluation;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

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
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalWidth('4xl'),
                Action::make('approve')
                    ->label(__('filament.approve'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Evaluation $record): bool => in_array($record->status, [
                        EvaluationStatuses::Published,
                        EvaluationStatuses::Disputed,
                    ], true))
                    ->schema([
                        Textarea::make('comment')
                            ->label(__('filament.approval_comment')),
                    ])
                    ->action(function (Evaluation $record, array $data): void {
                        $record->acceptByEmployee(
                            changedBy: Auth::id(),
                            comment: $data['comment'] ?? null
                        );
                    }),
                Action::make('dispute')
                    ->label(__('filament.dispute'))
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->visible(fn (Evaluation $record): bool => $record->status === EvaluationStatuses::Published)
                    ->schema([
                        Textarea::make('comment')
                            ->label(__('filament.dispute_reason'))
                            ->required(),
                    ])
                    ->action(function (Evaluation $record, array $data): void {
                        $record->disputeByEmployee(
                            changedBy: Auth::id(),
                            comment: $data['comment']
                        );
                    }),
            ]);
    }
}
