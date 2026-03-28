<?php

namespace App\Filament\QA\Widgets;

use App\Enums\EvaluationStatuses;
use App\Enums\QARoles;
use App\Models\Evaluation;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class QADisputeQueueTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Disputed evaluations';

    protected ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return Auth::user()?->hasRole(QARoles::Manager->value) ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('disputed_at', 'asc')
            ->query(
                Evaluation::query()
                    ->with(['employee', 'evaluator', 'qaForm'])
                    ->where('status', EvaluationStatuses::Disputed)
            )
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
                TextColumn::make('success_percentage')
                    ->label('Success %')
                    ->sortable(),
                TextColumn::make('threshold_percentage')
                    ->label('Threshold %')
                    ->sortable(),
                TextColumn::make('employee_decision_comment')
                    ->label('Employee comment')
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('disputed_at')
                    ->label('Disputed at')
                    ->since()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('resolve_dispute')
                    ->label('Resolve')
                    ->icon('heroicon-o-scale')
                    ->color('warning')
                    ->schema([
                        Select::make('resolution_status')
                            ->label('Resolution')
                            ->options([
                                EvaluationStatuses::AcceptedClosed->value => 'Accepted and Closed',
                                EvaluationStatuses::Rejected->value => 'Rejected',
                            ])
                            ->required(),
                        Textarea::make('comment')
                            ->label('Manager comment')
                            ->required(),
                    ])
                    ->action(function (Evaluation $record, array $data): void {
                        $record->resolveDispute(
                            resolutionStatus: EvaluationStatuses::from($data['resolution_status']),
                            changedBy: Auth::id(),
                            comment: $data['comment']
                        );
                    }),
            ]);
    }
}
