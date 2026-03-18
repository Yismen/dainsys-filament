<?php

namespace App\Filament\Admin\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityLogWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected static ?string $heading = 'Recent Activity';

    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('causer.name')
                    ->label('User')
                    ->wrap()
                    ->sortable()
                    ->searchable()
                    ->default('—'),
                TextColumn::make('description')
                    ->label('Action')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),
                TextColumn::make('subject_type')
                    ->label('Subject')
                    ->sortable()
                    ->formatStateUsing(function (?string $state) {
                        if (! $state) {
                            return '—';
                        }

                        return class_basename($state);
                    }),
                TextColumn::make('created_at')
                    ->label('Timestamp')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('description')
                    ->label('Action')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                        'User logged in' => 'User logged in',
                    ]),
                SelectFilter::make('subject_type')
                    ->label('Subject')
                    ->options(Activity::query()
                        ->select('subject_type')
                        ->distinct()
                        ->pluck('subject_type', 'subject_type')
                        ->mapWithKeys(fn ($value) => [$value => class_basename($value)])
                    )
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(true)
            ->defaultPaginationPageOption(5)
            ->striped();
    }

    private function getQuery(): Builder
    {
        return Activity::query()
            ->with('causer')
            ->latest();
    }
}
