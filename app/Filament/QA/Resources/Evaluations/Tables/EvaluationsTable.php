<?php

namespace App\Filament\QA\Resources\Evaluations\Tables;

use App\Enums\EvaluationStatuses;
use App\Enums\QARoles;
use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\User;
use App\Services\ModelListService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

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
                    ->date()
                    ->wrap()
                    ->wrapHeader()
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
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('success_percentage')
                    ->label('Success %')
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('threshold_percentage')
                    ->label('Threshold %')
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('points_achieved')
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('points_possible')
                    ->wrapHeader()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(EvaluationStatuses::class),
                SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->options(ModelListService::make(Employee::query(), value_field: 'full_name'))
                    ->searchable(),
                SelectFilter::make('evaluator_id')
                    ->label('Evaluator')
                    ->options(ModelListService::make(User::query()))
                    ->searchable(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('publish')
                        ->label('Publish')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->visible(function (Evaluation $record): bool {
                            $user = Auth::user();

                            if ($user === null) {
                                return false;
                            }

                            return $user->hasRole(QARoles::Agent->value)
                                && $record->evaluator_id === $user->id
                                && $record->status === EvaluationStatuses::Draft;
                        })
                        ->schema([
                            Textarea::make('comment')
                                ->label('Publish comment')
                                ->required(),
                        ])
                        ->action(function (Evaluation $record, array $data): void {
                            $record->publish(
                                changedBy: Auth::id(),
                                comment: $data['comment'] ?? null
                            );
                        }),
                    Action::make('resolve_dispute')
                        ->label('Resolve dispute')
                        ->icon('heroicon-o-scale')
                        ->color('warning')
                        ->visible(function (Evaluation $record): bool {
                            $user = Auth::user();

                            return $user !== null
                                && $user->hasRole(QARoles::Manager->value)
                                && $record->status === EvaluationStatuses::Disputed;
                        })
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
                ])->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => Auth::user()?->hasRole(QARoles::Manager->value) ?? false),
                    ForceDeleteBulkAction::make()
                        ->visible(fn (): bool => Auth::user()?->hasRole(QARoles::Manager->value) ?? false),
                    RestoreBulkAction::make()
                        ->visible(fn (): bool => Auth::user()?->hasRole(QARoles::Manager->value) ?? false),
                ]),
            ]);
    }
}
