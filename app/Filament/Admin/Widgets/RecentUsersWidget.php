<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentUsersWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected static ?string $heading = 'Recent Users';

    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('role')
                    ->getStateUsing(function (User $record) {
                        return $record->getRoleNames()->first() ?? 'No Role';
                    })
                    ->colors([
                        'danger' => 'admin',
                        'info' => 'user',
                        'warning' => 'supervisor',
                        'success' => 'employee',
                    ]),
                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false)
            ->striped()
            ->recordAction('edit');
    }

    private function getQuery(): Builder
    {
        return User::query()
            ->where('created_at', '>=', now()->subDays(7))
            ->limit(10);
    }
}
