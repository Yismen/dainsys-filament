<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentUsersWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected static ?string $heading = 'Recent Users';

    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('filament.email'))
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label(__('filament.role'))
                    ->getStateUsing(function (User $record) {
                        return $record->getRoleNames()->first() ?? 'No Role';
                    })
                    ->badge()
                    ->colors([
                        'danger' => 'admin',
                        'info' => 'user',
                        'warning' => 'supervisor',
                        'success' => 'employee',
                    ]),
                TextColumn::make('created_at')
                    ->label(__('filament.joined'))
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(true)
            ->defaultPaginationPageOption(5)
            ->striped();
    }

    private function getQuery(): Builder
    {
        return User::query();
    }
}
