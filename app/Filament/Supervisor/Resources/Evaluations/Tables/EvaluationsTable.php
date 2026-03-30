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
                    ->label('Record #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
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
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Evaluation $record): bool => in_array($record->status, [
                        EvaluationStatuses::Published,
                        EvaluationStatuses::Disputed,
                    ], true))
                    ->schema([
                        Textarea::make('comment')
                            ->label('Approval comment'),
                    ])
                    ->action(function (Evaluation $record, array $data): void {
                        $record->acceptByEmployee(
                            changedBy: Auth::id(),
                            comment: $data['comment'] ?? null
                        );
                    }),
                Action::make('dispute')
                    ->label('Dispute')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->visible(fn (Evaluation $record): bool => $record->status === EvaluationStatuses::Published)
                    ->schema([
                        Textarea::make('comment')
                            ->label('Dispute reason')
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
