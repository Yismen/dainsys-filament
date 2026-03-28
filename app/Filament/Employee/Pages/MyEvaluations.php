<?php

namespace App\Filament\Employee\Pages;

use App\Enums\EvaluationStatuses;
use App\Models\Evaluation;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MyEvaluations extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationLabel = 'My Evaluations';

    protected static ?string $title = 'My Evaluations';

    protected static ?int $navigationSort = 8;

    public function mount(): void
    {
        if (! Auth::user()?->employee_id) {
            abort(403, 'No employee record found.');
        }
    }

    public function getView(): string
    {
        return 'filament.pages.table-page';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Evaluation::query()
                    ->with(['qaForm', 'evaluator'])
                    ->where('employee_id', Auth::user()->employee_id)
            )
            ->defaultSort('evaluation_date', 'desc')
            ->columns([
                TextColumn::make('evaluation_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('qaForm.name')
                    ->label('QA Form')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('evaluator.name')
                    ->label('Evaluator')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('success_percentage')
                    ->label('Success %')
                    ->sortable(),
                TextColumn::make('threshold_percentage')
                    ->label('Threshold %')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(EvaluationStatuses::class),
            ])
            ->recordActions([
                Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Evaluation $record): bool => in_array($record->status, [
                        EvaluationStatuses::Published,
                        EvaluationStatuses::Disputed,
                    ], true))
                    ->schema([
                        Textarea::make('comment')
                            ->label('Acceptance comment'),
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
