<?php

namespace App\Filament\ProjectExecutive\Resources\Incentives;

use App\Filament\ProjectExecutive\Resources\Incentives\Pages\ListIncentives;
use App\Filament\ProjectExecutive\Resources\Incentives\Schemas\IncentiveInfolist;
use App\Filament\ProjectExecutive\Resources\Incentives\Tables\IncentivesTable;
use App\Models\Incentive;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class IncentiveResource extends Resource
{
    protected static ?string $model = Incentive::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $recordTitleAttribute = 'payable_date';

    protected static ?int $navigationSort = 10;

    protected static string|UnitEnum|null $navigationGroup = 'Payroll Information';

    public static function infolist(Schema $schema): Schema
    {
        return IncentiveInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncentivesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $managerId = Auth::id();

        if (! $managerId) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->whereHas('employee.project', function (Builder $query) use ($managerId): void {
                $query->where('manager_id', $managerId);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIncentives::route('/'),
        ];
    }
}
