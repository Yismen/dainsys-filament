<?php

namespace App\Filament\Workforce\Widgets;

use App\Enums\DowntimeStatuses;
use App\Models\Comment;
use App\Models\Downtime;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PendingDowntimesTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Pending downtime approvals';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('campaign.name')
                    ->label('Campaign')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('downtimeReason.name')
                    ->label('Reason')
                    ->sortable()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('total_time')
                    ->label('Minutes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('requester.name')
                    ->label('Requested by')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Requested at')
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Downtime $record): bool => $record->status === DowntimeStatuses::Pending)
                        ->schema([
                            \Filament\Forms\Components\Textarea::make('comment')
                                ->label('Approval comment')
                                ->required(),
                        ])
                        ->action(function (Downtime $record, array $data): void {
                            $record->aprove();

                            Comment::query()->forceCreate([
                                'text' => 'Approved: '.($data['comment'] ?? ''),
                                'commentable_id' => $record->id,
                                'commentable_type' => Downtime::class,
                            ]);
                        }),
                    Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Downtime $record): bool => $record->status === DowntimeStatuses::Pending)
                        ->schema([
                            \Filament\Forms\Components\Textarea::make('comment')
                                ->label('Rejection comment')
                                ->required(),
                        ])
                        ->action(function (Downtime $record, array $data): void {
                            $record->status = DowntimeStatuses::Rejected;
                            $record->aprover_id = null;
                            $record->saveQuietly();

                            if (method_exists($record, 'removeFromProduction')) {
                                $record->removeFromProduction();
                            }

                            Comment::query()->forceCreate([
                                'text' => 'Rejected: '.($data['comment'] ?? ''),
                                'commentable_id' => $record->id,
                                'commentable_type' => Downtime::class,
                            ]);
                        }),
                ])->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('approve_selected')
                        ->label('Approve selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->accessSelectedRecords()
                        ->schema([
                            \Filament\Forms\Components\Textarea::make('comment')
                                ->label('Approval comment')
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records
                                ->filter(fn (Downtime $record) => $record->status === DowntimeStatuses::Pending)
                                ->each(function (Downtime $record) use ($data): void {
                                    $record->aprove();

                                    Comment::query()->forceCreate([
                                        'text' => 'Approved: '.($data['comment'] ?? ''),
                                        'commentable_id' => $record->id,
                                        'commentable_type' => Downtime::class,
                                    ]);
                                });
                        }),
                    BulkAction::make('reject_selected')
                        ->label('Reject selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->accessSelectedRecords()
                        ->schema([
                            \Filament\Forms\Components\Textarea::make('comment')
                                ->label('Rejection comment')
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records
                                ->filter(fn (Downtime $record) => $record->status === DowntimeStatuses::Pending)
                                ->each(function (Downtime $record) use ($data): void {
                                    $record->status = DowntimeStatuses::Rejected;
                                    $record->aprover_id = null;
                                    $record->saveQuietly();

                                    if (method_exists($record, 'removeFromProduction')) {
                                        $record->removeFromProduction();
                                    }

                                    Comment::query()->forceCreate([
                                        'text' => 'Rejected: '.($data['comment'] ?? ''),
                                        'commentable_id' => $record->id,
                                        'commentable_type' => Downtime::class,
                                    ]);
                                });
                        }),
                ]),
            ]);
    }

    protected function getQuery(): Builder
    {
        return Downtime::query()
            ->where('status', DowntimeStatuses::Pending)
            ->with([
                'employee',
                'campaign',
                'downtimeReason',
                'requester',
            ]);
    }
}
