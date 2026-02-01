<?php

namespace App\Filament\Admin\Widgets;

use Filament\Tables\Columns\TextColumn;
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
                    ->searchable()
                    ->fallback('—'),
                TextColumn::make('description')
                    ->label('Action')
                    ->searchable()
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),
                TextColumn::make('subject_type')
                    ->label('Subject')
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
            ->defaultSort('created_at', 'desc')
            ->paginated(false)
            ->striped();
    }

    private function getQuery(): Builder
    {
        return Activity::query()
            ->limit(15);
    }
}
